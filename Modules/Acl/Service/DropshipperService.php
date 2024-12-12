<?php

namespace Modules\Acl\Service;

use App\Mail\VerificationCodeMail;
use Carbon\Carbon;
use Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Acl\Entities\Code;
use Modules\Acl\Entities\Dropshipper;
use Modules\Acl\Entities\DropshipperOption;
use Modules\Acl\Events\onboardingEmail;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperResource;
use Modules\Acl\Repositories\DropshipperRepository;
use Modules\Acl\Repositories\SendCodeRepository;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Service\DropshipperSegmentationService;
use Modules\Finance\Entities\Transaction;
use Modules\MasterCatalog\Service\ProductService;
use Modules\MasterCatalog\Service\ProfitService;

class DropshipperService extends BasicService
{
    protected $repo, $sendCodeRepository, $productService, $profitService, $dropshipperSegmentationService;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(
        DropshipperRepository $repository,
        SendCodeRepository $sendCodeRepository,
        ProductService $productService,
        ProfitService $profitService,
        DropshipperSegmentationService $dropshipperSegmentationService
    ) {
        $this->repo = $repository;
        $this->sendCodeRepository = $sendCodeRepository;
        $this->productService = $productService;
        $this->profitService = $profitService;
        $this->dropshipperSegmentationService = $dropshipperSegmentationService;
    }

    /**
     * This function finds data based on a request and returns it with optional pagination and
     * filtering options.
     *
     * param Request request An instance of the Request class, which contains the HTTP request
     * information.
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated, otherwise, all results will be returned.
     * param perPage The number of items to be displayed per page in the pagination.
     * param get The "get" parameter is used to specify the columns to retrieve from the database. It
     * is an optional parameter and if not provided, all columns will be retrieved.
     * param latest The "latest" parameter is a string that can be used to specify the column name to
     * order the results by in descending order. This can be useful when you want to retrieve the
     * latest records based on a specific column.
     * param limit The limit parameter is used to limit the number of results returned by the query.
     * It is an optional parameter and if not provided, all matching results will be returned.
     *
     * return the result of calling the `findBy` method on the repository object with the provided
     * parameters.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = "", $latest = '', $limit = null, $withRelations = [])
    {
        //todo change
        if (request()->input('search')) {
            $value = request()->input('search')['value'];
            $regex = request()->input('search')['regex'];
            if (($value == "as" || $value == "us") && $value != null) {
                $request->merge(['search' => [
                    'status' => activeType()[$value],
                    'regex' => $regex
                ]]);
                $request['status'] = activeType()[$value];
            }
        }
        return $this->repo->findBy($request, $pagination, $perPage, get: $get, limit: $limit, withRelations: $withRelations);
    }

    /**
     * This function retrieves data from a repository based on search and status filters, and returns it
     * with pagination.
     *
     * param request This parameter is likely an instance of the Illuminate\Http\Request class, which
     * represents an HTTP request made to the application. It contains information about the request
     * such as the HTTP method, URL, headers, and any data sent in the request body.
     *
     * return the result of a database query using the `findBy` method of a repository object. The
     * query is filtered based on the values of the `` object, as well as additional conditions
     * specified in the `` array. The result is paginated and sorted
     * according to the `` variable and the `` parameter. The ``
     */
    public function indexDashboard($request)
    {
        //todo change
        $tableLength = session('table_length') ?? config('app.pagination_pages');
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];
        if (isset($request->search) && $request->search != null) {
            $moreConditionForFirstLevel += [
                'whereCustom' => [
                    'orWhere' => [['email' => ['LIKE', '%' . $request->search . '%']],['id' => ['LIKE', '%' . $request->search . '%']]]
            ]];
        }
        if ($request->fromDate && $request->toDate && $request->is_report == 0) {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]]];
        } elseif ($request->fromDate && $request->is_report == 0) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)
                ->startOfDay()]]];
        } elseif ($request->toDate && $request->is_report == 0) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', Carbon::parse($request->toDate)
                ->endOfDay()]]];
        }
        if (isset($request->is_report) && $request->is_report) {
            if ($request->fromDate && $request->toDate) {
                $recursiveRel = [
                    'order' => [
                        'type' => 'whereHas',
                        'whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                            ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]],
                        'recursive' => [
                            'orderItems' => [
                                'type' => 'whereHas',
                                'recursive' => [
                                    'product' => [
                                        'type' => 'whereHas',
                                    ]
                                ],
                            ]
                        ],
                    ]
                ];
                if (isset($request->product_id) && empty($request->product_id)) {
                    $recursiveRel['order']['recursive']['orderItems']['recursive']['product']['whereBetween'] = ['created_at' => [Carbon::parse($request->fromDate)
                        ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]];
                }
            } elseif ($request->fromDate) {
                $recursiveRel = [
                    'order' => [
                        'type' => 'whereHas',
                        'where' => ['created_at' => ['>=', Carbon::parse($request->fromDate)->endOfDay()]],
                        'recursive' => [
                            'orderItems' => [
                                'type' => 'whereHas',
                                'recursive' => [
                                    'product' => [
                                        'type' => 'whereHas',
                                    ]
                                ],
                            ]
                        ],
                    ]
                ];
                if (isset($request->product_id) && empty($request->product_id)) {
                    $recursiveRel['order']['recursive']['orderItems']['recursive']['product']['where'] = ['created_at' => ['>=', Carbon::parse($request->fromDate)
                        ->endOfDay()]];
                }
            } elseif ($request->toDate) {
                $recursiveRel = [
                    'order' => [
                        'type' => 'whereHas',
                        'where' => ['created_at' => ['<=', Carbon::parse($request->toDate)->endOfDay()]],
                        'recursive' => [
                            'orderItems' => [
                                'type' => 'whereHas',
                                'recursive' => [
                                    'product' => [
                                        'type' => 'whereHas',
                                    ]
                                ],
                            ]
                        ],
                    ]
                ];
                if (isset($request->product_id) && empty($request->product_id)) {
                    $recursiveRel['order']['recursive']['orderItems']['recursive']['product']['where'] = ['created_at' => ['<=', Carbon::parse($request->toDate)
                        ->endOfDay()]];
                }
            }
            if (isset($request->dropshipper_id) && !empty($request->dropshipper_id)) {
                $moreConditionForFirstLevel += ['whereIn' => ['id' => $request->dropshipper_id]];
            }
            if (isset($request->supplier_id) && !empty($request->supplier_id)) {
                $recursiveRel['order']['recursive']['orderItems']['recursive']['product']['whereIn'] = ['supplier_id' => $request->supplier_id];
            }
            if (isset($request->product_id) && !empty($request->product_id)) {
                $recursiveRel['order']['recursive']['orderItems']['where'] = ['product_id' => $request->product_id];
            }
            if (isset($request->warehouse_id) && !empty($request->warehouse_id)) {
                $recursiveRel['order']['recursive']['orderItems']['recursive']['product']['whereIn'] = ['supplier_id' => $request->warehouse_id];
            }
        }
        return $this->repo->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: true,
            perPage: $tableLength,
            recursiveRel: $recursiveRel,
            orderBy: ['column' => 'id', 'order' => 'desc']
        );
    }

    /**
     * The function saves a request, generates a token, sends an email, and returns a resource.
     *
     * param Request request  is an instance of the Request class which contains the data
     * submitted by the user through a form or an API request. It contains information such as the HTTP
     * method, headers, and the data submitted in the request body. In this case, it is being used to
     * save the data submitted by the user
     *
     * return If the `` is successfully saved, a new `DropshipperResource` instance is returned.
     * If not, `false` is returned.
     */
    public function store(Request $request)
    {
        $request->merge(['onboarding_questionnaire_number' => 1]);
        $dropshipper_segmentation_id = $this->dropshipperSegmentationService->findBy(
            new Request(),
            orderBy: ['column' => 'from', 'order' => 'asc'],
            get: 'first'
        );
        $request->merge(['dropshipper_segmentation_id' => $dropshipper_segmentation_id->id]);
        $data = $this->repo->save($request);
        if ($data) {
            //todo change
            $token = $data->createToken('olltek Personal Access Client')->accessToken;
            $data->update(['token' => $token]);
            $this->sendMail($data);
            return new DropshipperResource($data);
        }
        return false;
    }

    public function sendMail($data)
    {
        $now = Carbon::now();
        // Your authentication logic here
        $codeOld = $this->sendCodeRepository->findBy(new Request(['dropshipper_id' => $data->id]), get: 'first');
        if (is_null($codeOld)) {
            $code = rand(1000, 9999);
            $this->saveVerificationCode(['code' => $code, 'dropshipper_id' => $data->id, 'expireResendCodeAt' => $now->addHours(3)]);
        } elseif ($now > Carbon::parse($codeOld->expireResendCodeAt)) {
            $code = rand(1000, 9999);
            $this->saveVerificationCode(['code' => $code, 'expireResendCodeAt' => $now->addHours(3)], $codeOld->id);
        } else {
            $code = $codeOld->code;
            $this->saveVerificationCode(['expireResendCodeAt' => $now->addHours(3)], $codeOld->id);
        }
        $data->update(['code' => $code]);
        // Send email
        $this->sendVerificationEmail($data->email, $code);
        return true;
        // Send SMS
        // return $this->sendVerificationSMS($data->phone, $code);
    }

    public function sendMailCode($data,$code)
    {
        $now = Carbon::now();
        $codeOld = $this->sendCodeRepository->findBy(new Request(['dropshipper_id' => $data->id]), get: 'first');
        if(is_null($codeOld))
        {
            $this->saveVerificationCode(['code' => $code, 'dropshipper_id' => $data->id, 'expireResendCodeAt' => $now->addHours(3)]);
        }elseif($now > Carbon::parse($codeOld->expireResendCodeAt))
        {
            $this->saveVerificationCode(['code' => $code, 'expireResendCodeAt' => $now->addHours(3)], $codeOld->id);
        }else
        {
            $this->saveVerificationCode(['code' => $code, 'expireResendCodeAt' => $now->addHours(3)], $codeOld->id);
        }
        $data->update(['code' => $code]);
        // Send email
        $this->sendVerificationEmail($data->email, $code);
        return true;
        // Send SMS
        // return $this->sendVerificationSMS($data->phone, $code);
    }

    private function saveVerificationCode($requestData, $id = null)
    {
        $request = new Request($requestData);
        if ($id === null) {
            $this->sendCodeRepository->save($request);
        } else {
            $this->sendCodeRepository->save($request, $id);
        }
    }

    private function sendVerificationEmail($email, $code)
    {
        Mail::to($email)->send(new VerificationCodeMail($code));
    }

    private function sendVerificationSMS($phone, $code)
    {
        $sms = app()->make(SMS::class);
        $message = "كود التحقق الخاص بك هو: {$code}";
        return $sms->send($phone, 'OLLTEK', $message);
    }

    /**
     * It takes an email address, finds the user in the database, and sends them a new email.
     *
     * param Request request The request object
     *
     * return The return value is the result of the sendMail method.
     */
    public function resendCode()
    {
        $user = user();

        return $this->sendMail($user);
    }

    /**
     * It takes a request, passes it to the repo, and returns the result of the repo's save method.
     *
     * param Request request The request object
     *
     * return The data is being returned.
     */
    public function updateProfit(Request $request)
    {
        $data = $this->repo->save($request, user()->id);
        if ($data) {
            return new DropshipperResource($data);
        }
        return false;
    }

    /**
     * It saves the data from the request, creates a token, updates the token in the database, and
     * sends an email
     *
     * param Request request The request object.
     *
     * return A new DropshipperResource()
     */
    public function storeStepOne(Request $request)
    {
        $data = $this->repo->save($request);
        if ($data) {
            //todo change
            $token = $data->createToken('olltek Personal Access Client')->accessToken;
            $data->update(['token' => $token]);
            $this->sendMail($data);
            return new DropshipperResource($data);
        }
        return false;
    }

    /**
     * It takes a request, finds a user by email, checks if the code is correct, then updates the
     * user's isVerified and code fields
     *
     * param Request request The request object.
     *
     * return A new DropshipperResource()
     */
    public function storeStepTwo(Request $request)
    {
        $user = user();
        if (!$user || $user->code != $request->code) {
            return false;
        }
        //todo change
        $code = Code::where('dropshipper_id', $user->id)->latest()->first();
        $now = Carbon::now();
        if ($now->greaterThan($code->expireResendCodeAt)) {
            return 'invalidTime';
        }
        $request->merge(['isVerified' => 1, 'code' => null]);
        $data = $this->repo->save($request, $user->id);
        if ($data) {
            return new DropshipperResource($data);
        }
    }


    public function verificationCode($user,Request $request)
    {

        if(!$user || $user->code != $request->code)
        {
            return false;
        }
        //todo change
        $code = Code::where('dropshipper_id', $user->id)->latest()->first();
        $now = Carbon::now();
        if($now->greaterThan($code->expireResendCodeAt))
        {
            return 'invalidTime';
        }
        $request->merge(['isVerified' => 1, 'code' => null]);
        $data = $this->repo->save($request, $user->id);
        if($data)
        {
            return new DropshipperResource($data);
        }
    }




    /**
     * It takes a request, finds a user by email, saves the request to the database, creates a token,
     * and returns a resource.
     *
     * param Request request the request object
     *
     * return A new DropshipperResource()
     */
    public function storeStepThree(Request $request)
    {
        $data = $this->repo->save($request, user()->id);
        if ($data) {
            return new DropshipperResource($data);
        }
        return false;
    }

    /**
     * It takes a request, and saves it to the database
     *
     * param Request request the request object
     *
     * return A new DropshipperResource
     */
    public function changePhoneNumber(Request $request)
    {
        $user = user();
        $request->merge(['isVerified' => 0, 'phone' => $request->phone]);
        $data = $this->repo->save($request, $user->id);
        $this->sendMail($data);
        if ($data) {
            return new DropshipperResource($data);
        }
        return false;
    }

    /**
     * It deletes the user's account
     *
     * param Request request The request object
     *
     * return The data is being returned.
     */
    public function destroy(Request $request, $id = null)
    {
        $data = $this->repo->delete(user()->id);
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * This function updates a dropshipper's information and returns a DropshipperResource object if
     * successful.
     *
     * param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request. It can contain data from the request body, query parameters,
     * headers, etc. In this case, it is being used to update a Dropshipper resource.
     *
     * return If the `` variable is truthy, an instance of `DropshipperResource` containing the
     * updated data is returned. Otherwise, `false` is returned.
     */
    public function update(Request $request, $id = null)
    {
        if ($request->amount) {
            $request->merge([
                'walletBalance' => user()->walletBalance - $request->amount,
            ]);
        }
        $data = $this->repo->save($request, user()->id);
        if ($data) {
            return new DropshipperResource($data);
        }
        return false;
    }

    /**
     * This PHP function updates the wallet balance of a user by subtracting the approved withdrawal
     * amount.
     *
     * param amount The amount of money to be withdrawn from the user's wallet balance.
     *
     * return either a new DropshipperResource object if the data is saved successfully, or false if
     * it fails.
     */
    public function withdrawalBalanceByApproved($data)
    {
        $request = request();
        if ($data) {
            $dropshipper = $this->repo->findOne($data->dropshipper_id);
            $request->merge([
                'earningsWithdrawal' => $dropshipper->earningsWithdrawal - $data->amount,
                'status' => user()->status,
            ]);
        }
        $data = $this->repo->save($request, $dropshipper->id);
        if ($data) {
            return true;
        }
        return false;
    }

    /**
     * The function `depositBalanceByApproved` deducts the specified amount from a dropshipper's wallet
     * balance and updates the status of the user.
     *
     * param data The "data" parameter is an object that contains information about the deposit
     * transaction. It likely includes properties such as "dropshipper_id" (the ID of the dropshipper
     * making the deposit) and "amount" (the amount being deposited).
     *
     * return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function depositBalanceByApproved($data)
    {
        $request = request();
        if ($data) {
            $dropshipper = $this->repo->findOne($data->dropshipper_id);
            $request->merge([
                'earningsWithdrawal' => $dropshipper->earningsWithdrawal + $data->amount,
                'status' => user()->status,
            ]);
        }
        $data = $this->repo->save($request, $dropshipper->id);
        if ($data) {
            return true;
        }
        return false;
    }

    /**
     * This function updates the wallet balance of a user by adding the amount of a given wallet.
     *
     * param wallet The  parameter is likely an object representing a user's wallet, which
     * contains information such as the wallet ID, user ID, and current balance. The function appears
     * to update the user's wallet balance by adding the amount specified in the  object to the
     * user's current wallet balance.
     *
     * return the result of calling the `save` method on the repository object with the updated wallet
     * balance value and the user ID as arguments.
     */
    public function updateWalletBalance($wallet)
    {
        $request = request();
        $request->merge([
            'walletBalance' => $wallet->dropshipper->walletBalance + $wallet->amount,
            'status' => $wallet->dropshipper->status,
        ]);
        return $this->repo->save($request, $wallet->dropshipper_id);
    }

    public function megaRegister($request)
    {
        $request->merge(['profit' => Dropshipper::MEGA_PROFIT]);
        $data = $this->repo->save($request);
        $data->update([
            'mega' => true,
        ]);
        if ($data) {
            $token = $data->createToken('olltek Personal Access Client')->accessToken;
            $data->update(['token' => $token]);
            return new DropshipperResource($data);
        }
        return false;
    }

    /**
     * The function updates the wallet balance of a user by subtracting the grand total of an order and
     * saves the changes.
     *
     * param order The  parameter is an object that represents an order. It likely contains
     * information such as the order's grand total and the dropshipper ID.
     *
     * return the result of the save method from the repository.
     */
    public function updateWalletBalanceByPayWallet($order)
    {
        $dropshipper = $this->show($order->dropshipper_id);
        $request= new Request([
            'walletBalance' => ($dropshipper->walletBalance - $order->grandTotal),
            'status' => $order->dropshipper->status,
        ]);
        return $this->repo->save($request, $order->dropshipper_id);
    }

    /**
     * The function `subscriptionPlan` takes a request, a plan, and an amount as parameters, merges
     * additional data into the request, and saves the request with the user's ID.
     *
     * param request The  parameter is an instance of the Illuminate\Http\Request class. It
     * represents the HTTP request made to the server and contains information such as the request
     * method, headers, and input data.
     * param plan The "plan" parameter is an object that represents a subscription plan. It likely
     * contains information such as the plan's ID, name, price, and duration.
     * param amount The amount parameter is the amount of the subscription plan. It represents the
     * cost or price of the plan.
     *
     * return the result of the `save` method called on the `repo` object, passing in the ``
     * and `user()->id` as arguments.
     */
    public function subscriptionPlan($request, $plan, $amount)
    {
        $request->merge([
            'walletBalance' => user()->walletBalance - $amount,
            'plan_id' => $plan->id,
            'expirePlanAt' => ($request->type === 'monthly') ? Carbon::now()->addMonth(1)
                ->format('Y-m-d') : Carbon::now()->addMonth(12)->format('Y-m-d'),
        ]);
        return $this->repo->save($request, user()->id);
    }

    /**
     * This function updates the wallet balance of a user by adding the amount of a given wallet.
     *
     * param wallet The  parameter is likely an object representing a user's wallet, which
     * contains information such as the wallet ID, user ID, and current balance. The function appears
     * to update the user's wallet balance by adding the amount specified in the  object to the
     * user's current wallet balance.
     *
     * return the result of calling the `save` method on the repository object with the updated wallet
     * balance value and the user ID as arguments.
     */
    public function cronJopUpdateProfitBalance()
    {
        //todo change
        $transactions = Transaction::where('isStatus', 0)->get();
        foreach ($transactions as $transaction) {
            $dateNow = date('Y-m-d');
            if ($dateNow > Carbon::parse($transaction->created_at)->addDays(7)->format('Y-m-d')) {
                $transaction->isStatus = 1;
                if ($transaction->save()) {
                    $dropshipper = Dropshipper::find($transaction->dropshipper_id);
                    $dropshipper->profitBalance = $dropshipper->profitBalance + $transaction->profitRatio;
                    $dropshipper->earningsWithdrawal = $dropshipper->earningsWithdrawal + $transaction->profitRatio;
                    $dropshipper->status = $dropshipper->status;
                    $dropshipper->save();
                }
            }
            echo 'done';
        }
    }

    /**
     * The function updates the wallet balance of a user by adding the refund balance from an order and
     * returns the updated balance.
     *
     * param orderRefund The  parameter is an object that represents the refund details of
     * an order. It likely contains information such as the refund amount, order ID, and any other
     * relevant refund details.
     * param dropshipper_id The dropshipper_id parameter is the ID of the dropshipper whose wallet
     * balance needs to be updated.
     *
     * return the result of the `save` method from the repository, which is being called with the
     * modified request and the `dropshipper_id` as arguments.
     */
    public function updateWalletBalanceByRefundBalance($orderRefund, $dropshipper_id)
    {
        //todo change
        $dropshipper = $this->repo->findOne($dropshipper_id);
        return $dropshipper->update([
            'walletBalance' => $dropshipper->walletBalance + $orderRefund->grandTotal,
            'status' => $dropshipper->status,
        ]);
    }

    public function export(Request $request)
    {
        $moreConditionForFirstLevel = [];
        $recursiveRel = [];
        //todo change
        if (isset($request->search) && $request->search != null) {
            $moreConditionForFirstLevel += ['where' => ['email' => ['LIKE', '%' . $request->search . '%']]];
        }
        if ($request->fromDate && $request->toDate) {
            $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [Carbon::parse($request->fromDate)
                ->startOfDay(), Carbon::parse($request->toDate)->endOfDay()]]];
        } elseif ($request->fromDate) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['>', Carbon::parse($request->fromDate)
                ->startOfDay()]]];
        } elseif ($request->toDate) {
            $moreConditionForFirstLevel += ['where' => ['created_at' => ['<', Carbon::parse($request->toDate)
                ->endOfDay()]]];
        }
        return $this->repo->findBy(
            $request,
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            pagination: false,
            perPage: 0,
            recursiveRel: $recursiveRel,
            orderBy: ['column' => 'id', 'order' => 'desc']
        );
    }

    public function list(Request $request)
    {
        $moreConditionForFirstLevel = [];
        if (isset($request->term) && $request->term != null) {
            $moreConditionForFirstLevel += ['orWhere' => ['id' => [$request->term], 'email' => ['LIKE', '%' . $request->term . '%'], 'phone' => ['LIKE', '%' . $request->term . '%'], 'store_name' => ['LIKE', '%' . $request->term . '%']]];
        }
        return $this->repo->findBy(new Request(), moreConditionForFirstLevel: $moreConditionForFirstLevel);
    }

    public function onboarding_questionnaire(Request $request)
    {
        $dropshipper = user();
        if ($dropshipper->onboarding_questionnaire_number == 0) {
            return false;
        }
        switch ($request->onboarding_questionnaire_number) {
            case 1:
                $request->merge(['onboarding_questionnaire_number' => 2]);
                break;
            case 2:
                $request->merge(['onboarding_questionnaire_number' => 3]);
                break;
            case 3:
                $request->merge(['onboarding_questionnaire_number' => 4]);
                break;
            case 4:
                $request->merge(['onboarding_questionnaire_number' => 5]);
                break;
            case 5:
                $request->merge(['onboarding_questionnaire_number' => 6]);
                break;
            case 6:
                $request->merge(['onboarding_questionnaire_number' => 0]);
                break;
            default:
                $request->merge(['onboarding_questionnaire_number' => 1]);
                break;
        }
        $data = $this->repo->save($request, user()->id);
        if ($data->onboarding_questionnaire_number == 0) {
            Event::dispatch(new onboardingEmail(user()->email));
        }
        return $data;
    }

    public function changeStatusDropshipperSetting($dropshipper_id,$dropshipper_setting_id){
    $dropOption=    DropshipperOption::where('dropshipper_id',$dropshipper_id)->where('dropshipper_setting_id',$dropshipper_setting_id)->first();
     if($dropOption){
        $dropOption->delete();
        return false;
     }else{
        $dropOption = array('dropshipper_id' => $dropshipper_id, 'dropshipper_setting_id' => $dropshipper_setting_id);
        DropshipperOption::create($dropOption);
        return true;
     }


  
    }
}
