<?php

namespace Modules\Finance\Service;

use Exception;
use Illuminate\Http\Request;
use Modules\Finance\Enums\ProfitEnum;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderItem;
use Modules\Basic\Service\BasicService;
use Modules\Finance\Entities\Transaction;
use Modules\MasterCatalog\Service\ProductService;
use Modules\Finance\Repositories\TransactionRepository;
use Modules\Finance\Actions\Transaction\StoreTransactionAction;
use Modules\Finance\Actions\Transaction\PaymentProfitTransactionAction;

class TransactionService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(TransactionRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function finds data based on a request and returns it with optional pagination and
     * filtering.
     *
     * param Request request  is an instance of the Request class in Laravel, which contains
     * all the data that was sent with the HTTP request.
     *
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     *
     * param perPage The number of results to be displayed per page in case pagination is enabled.
     *
     * param get The "get" parameter is a string that specifies which relationships to eager load when
     * retrieving the data.
     *
     * return The `findBy` method is being returned, which is called on the `->repo` object with
     * the parameters ``, ``, ``, and ``.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = "")
    {
        return $this->repo->findBy($request, $pagination, $perPage, get: $get);
    }

    /**
     * This function stores a deposit transaction with specific details related to a new order.
     *
     * param order order  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request, such as the HTTP
     * method, URL, headers, and any data sent in the request body.
     *
     * return the result of calling the `save()` method on the `->repo` object, passing in the
     * modified `` object as an argument.
     */
    public function store($order)
    { //todo change
        return App(StoreTransactionAction::class)->execute($order);
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
        if ($this->repo->delete(user()->id)) {

            return $this->repo->delete(user()->id);
        }

        return false;
    }

    /**
     * The function calculates the payment profit for a dropshipper based on their outstanding and
     * pending orders.
     *
     * param Request request an instance of the Illuminate\Http\Request class, which contains the HTTP
     * request information.
     *
     * return an array with three keys: 'pendingBalance', 'outstandingBalance', and
     * 'balanceDetailsTables'
     */
    public function PaymentProfit(Request $request)
    {
        return App(PaymentProfitTransactionAction::class)->execute($request);
    }

    public function totalProfit($product, $item)
    {

        $result = ($item['unitPrice'] - $product->cost_price) * $item['quantity'];
        return  $result;
    }

    public function vatProfit($product, $item)
    {
        $totalProfit =  $item['unitPrice'] - $product->cost_price;
        $result = ($totalProfit * setting('shipping_fee')) * $item['quantity'];
        return $result;
    }

    public function netProfit($product, $item)
    {
        $netProfit = $this->totalProfit($product, $item) - $this->vatProfit($product, $item);
        $result = $netProfit;
        return ($result < 0) ? 0 : $result;
    }

    public function productVat($product, $item)
    {
        $result = ($product->cost_price * setting('shipping_fee')) * $item['quantity'];
        return ($result < 0) ? 0 : $result;
    }


    public function checkOrderAndCalculatorPrice($items, $order = null): array
    {
        $totalProductVat = [];
        $netProfit = [];

        foreach ($items as $item) {
            $product =  app()->make(ProductService::class)->show($item['product_id']);
            $totalProductVat[] = $this->getOrderItemVat($product, $item);
            $netProfit[] = $this->netProfit($product, $item);
        }

        return [
            'totalProductVat' => $totalProductVat,
            'netProfit' => $netProfit,
        ];
    }

    public function getOrderItemVat($product, $item)
    {
        $itemSellingPrice = $item['unitPrice'];

        $productBasePrice = $product->is_discount ? $product->priceAfterDiscount : $product->cost_price;
        $baseVat = $productBasePrice * setting('shipping_fee');
        $totalProfit = $itemSellingPrice - $productBasePrice;
        $profitVat = $totalProfit * setting('shipping_fee');
        $netProfit = $totalProfit - $profitVat;
        $totalVat = $baseVat + $profitVat;

        // return ($totalVat * $item['quantity']);
        return ($totalVat);
    }

    public function updatedTransactionStatus($request,$ids)
    {
        if($ids)
        {
        $transactions = $this->repo->findBy(new Request(['order_id' => $ids]));
        foreach($transactions as $transaction)
        {
            $this->repo->save($request, $transaction->id);
        }
        }
    }

    public function listWallet(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->repo->findBy($request->merge(['dropshipper_id'=>user()->id,'isStatus'=>ProfitEnum::WALLETE_DONE]),$pagination,$perPage,orderBy:['column' => 'earning_date', 'order' => 'desc']);
    }
}
