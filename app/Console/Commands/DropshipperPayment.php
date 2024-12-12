<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Modules\Acl\Entities\DropshipperPayment as Payment;
use Modules\Acl\Service\DropshipperService;
use Modules\Finance\Service\WithdrawalRequestService;

class DropshipperPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropshipper:dropshipper-payments';
    protected $service, $withdrawalRequestService;
    protected $description = 'Command description';

    public function __construct(DropshipperService $service, WithdrawalRequestService $withdrawalRequestService)
    {
        parent::__construct();
        $this->service = $service;
        $this->withdrawalRequestService = $withdrawalRequestService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dropshippers = $this->service->findBy(new Request());
        foreach($dropshippers as $dropshipper)
        {
            if($dropshipper->iban || $dropshipper->bankAccountName || $dropshipper->accountNumber)
            {
                Payment::create(['dropshipper_id' => $dropshipper->id,
                    'beneficiary_mobile' => null,
                    'beneficiary_address' => null,
                    'beneficiary_name' => $dropshipper->bankAccountName,
                    'iban' => $dropshipper->iban,
                    'swift_number' => null,
                    'bank_address' => null,
                    'bank_name' => $dropshipper->bankName,
                    'currency' => null,
                    'account_number' => $dropshipper->accountNumber,
                ]);
            }
        }
        $withdrawalRequests = $this->withdrawalRequestService->findBy(new Request());
        foreach($withdrawalRequests as $withdrawalRequest)
        {
            if($withdrawalRequest->iban)
            {
                $payment = Payment::where('dropshipper_id', $withdrawalRequest->dropshipper_id)
                    ->where(function($query) use ($withdrawalRequest)
                    {
                        $query->where('iban', $withdrawalRequest->iban)
                            ->orWhere('account_number', $withdrawalRequest->account_number);
                    })->first();
                if($payment)
                {
                    $payment->update(['dropshipper_id' => $withdrawalRequest->dropshipper_id,
                        'bank_name' => $withdrawalRequest->bank_name,
                        'account_number' => $withdrawalRequest->account_number,
                        'beneficiary_mobile' => $withdrawalRequest->beneficiary_mobile,
                        'beneficiary_name' => $withdrawalRequest->beneficiary_name,
                        'beneficiary_address' => $withdrawalRequest->beneficiary_address,
                        'iban' => $withdrawalRequest->iban,
                        'swift_number' => $withdrawalRequest->swift_no,
                        'bank_address' => $withdrawalRequest->bank_address,
                        'currency' => null,
                    ]);
                }else
                {
                    $payment = Payment::create(['dropshipper_id' => $withdrawalRequest->dropshipper_id,
                        'bank_name' => $withdrawalRequest->bank_name,
                        'account_number' => $withdrawalRequest->account_number,
                        'beneficiary_mobile' => $withdrawalRequest->beneficiary_mobile,
                        'beneficiary_name' => $withdrawalRequest->beneficiary_name,
                        'beneficiary_address' => $withdrawalRequest->beneficiary_address,
                        'iban' => $withdrawalRequest->iban,
                        'swift_number' => $withdrawalRequest->swift_no,
                        'bank_address' => $withdrawalRequest->bank_address,
                        'currency' => null,
                    ]);
                }
            }
            $withdrawalRequest->update(['dropshipper_payment_id'=>$payment->id ?? 0]);
        }
        $this->info('dropshipper:dropshipper-payments Command Run successfully!');
    }
}
