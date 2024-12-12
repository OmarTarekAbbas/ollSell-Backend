<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Modules\Acl\Entities\Dropshipper;
use Modules\Finance\Entities\DepositRequest;
use Modules\Finance\Entities\Transaction;
use Modules\Finance\Entities\WithdrawalRequest;
use Modules\Finance\Enums\ProfitEnum;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Enums\PaymentEnum;

class CheckProfit extends Command
{
    protected $signature = 'finance:check-profit';
    protected $description = 'check profit';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $ws = WithdrawalRequest::all();
        foreach($ws as $w)
        {
            $w->update(['order_id'=>null]);
        }
        $transactions = Transaction::all();
        foreach($transactions as $transaction)
        {
            $isStatus = ProfitEnum::PENDING;
            $dateNow = date('Y-m-d');
            if($dateNow > Carbon::parse($transaction->order->deliveryDate)->addDays(7)->format('Y-m-d'))
            {
                $isStatus = $transaction->order->status_id == OrderEnum::REFUND_STATUS
                    ? ProfitEnum::REFUNDED
                    : ProfitEnum::AVAILABLE;
            }
            $transaction->update([
                'dropshipper_id' => $transaction->order->dropshipper_id,
                'withdrawal_request_id' => null,
                'earning_type' => null,
                'earning_date' => null,
                'isStatus'=>$isStatus,
                'costPrice' => $transaction->order->costPrice,
                'sellingPrice' => $transaction->order->subTotal,
                'profitRatio' => $transaction->order->net_profit
            ]);
        }
        $transactions = Transaction::all();
        foreach($transactions as $transaction)
        {
            $order = Order::find($transaction->order_id);
            if(!$order || !in_array($order->status_id,
                    [OrderEnum::COMPLETED_STATUS, OrderEnum::REFUND_STATUS]))
            {
                $transaction->delete();
            }
        }
        $orders = Order::whereIn('status_id', [OrderEnum::COMPLETED_STATUS, OrderEnum::REFUND_STATUS])
            ->get();
        foreach($orders as $order)
        {
            $transactions = Transaction::where('order_id', $order->id)->get();
            $transactionCount = $transactions->count();
            if($transactionCount > 1)
            {
                foreach($transactions as $transaction)
                {
                    $transaction->delete();
                }
            }
            $request = new Request();
            $isStatus = ProfitEnum::PENDING;
            $dateNow = date('Y-m-d');
            if($dateNow > Carbon::parse($order->deliveryDate)->addDays(7)->format('Y-m-d'))
            {
                $isStatus = $order->status_id == OrderEnum::REFUND_STATUS
                    ? ProfitEnum::REFUNDED
                    : ProfitEnum::AVAILABLE;
                $request->merge(['isStatus' => $isStatus]);
            }
            if($transactionCount == 0)
            {
                $request->merge([
                    'paymentMethod' => $order->paymentMethod,
                    'totalOrder' => $order->grandTotal,
                    'costPrice' => $order->costPrice,
                    'sellingPrice' => $order->subTotal,
                    'profitRatio' => $order->net_profit,
                    'order_id' => $order->id,
                    'dropshipper_id' => $order->dropshipper_id,
                    'isStatus' => $isStatus,
                ]);
                app('Modules\Finance\Repositories\TransactionRepository')->save($request);
            }else
            {
                $transaction->isStatus = $isStatus;
                $transaction->save();
            }
        }
        $dropshippers = Dropshipper::all();
        foreach($dropshippers as $dropshipper)
        {
            $this->updateDropshipperProfit($dropshipper);
        }
    }

    private function updateDropshipperProfit($dropshipper)
    {
        // Fetch transactions only once
        $transactions = Transaction::where('dropshipper_id', $dropshipper->id)
            ->whereNot('isStatus', ProfitEnum::PENDING)
            ->get();
        $profitBalance = $transactions->sum('profitRatio');
        $dropshipper->profitBalance = $profitBalance;
        $dropshipper->earningsWithdrawal = $profitBalance; // Same as profitBalance
        $refundedProfit = $transactions->where('isStatus', ProfitEnum::REFUNDED)->sum('profitRatio');
        $withdrawalAmount = WithdrawalRequest::where('dropshipper_id', $dropshipper->id)
            ->whereIn('status', [WithdrawalRequest::PENDING_STATUS, WithdrawalRequest::APPROVED_STATUS])
            ->sum('amount');
        $dropshipper->earningsWithdrawal -= ($refundedProfit + $withdrawalAmount);
        $depositAmount = DepositRequest::where('dropshipper_id', $dropshipper->id)
            ->where('status', DepositRequest::APPROVED_STATUS)
            ->sum('amount');
        $dropshipper->walletBalance = $depositAmount;
        $withdrawalAmount = WithdrawalRequest::where('dropshipper_id', $dropshipper->id)
            ->whereIn('status', [WithdrawalRequest::PENDING_STATUS, WithdrawalRequest::APPROVED_STATUS])
            ->orderBy('status', 'asc')
            ->get();
        foreach($withdrawalAmount as $withdrawal)
        {
            $transactions = Transaction::where('dropshipper_id', $dropshipper->id)
                ->where('isStatus', ProfitEnum::AVAILABLE)
                ->orderBy('profitRatio', 'desc')
                ->pluck('profitRatio', 'id')->toArray();
            $target = $withdrawal->amount;
            if(count($transactions))
            {
                $resultKeys = $this->getSubsetSumToTarget($transactions, $target);
                if(count($resultKeys))
                {
                    $transactions = Transaction::whereIn('id', $resultKeys)->get();
                    foreach($transactions as $transaction)
                    {
                        $status = ProfitEnum::WITHDRAWAL_DONE;
                        if($withdrawal->status == WithdrawalRequest::PENDING_STATUS)
                        {
                            $status = ProfitEnum::WITHDRAWAL_PENDING;
                        }
                        $this->updateTransaction($transaction, $status, ProfitEnum::WITHDRAWAL,
                            $withdrawal->id);
                        $this->updateWithdrawal($withdrawal, $transaction->order_id);
                    }
                }else
                {
                    if($withdrawal->status == WithdrawalRequest::PENDING_STATUS)
                    {
                        $withdrawal->status = WithdrawalRequest::REJECTED_STATUS;
                        $dropshipper->earningsWithdrawal += $withdrawal->amount;
                        $withdrawal->save();
                    }
                }
            }else
            {
                if($withdrawal->status == WithdrawalRequest::PENDING_STATUS)
                {
                    $withdrawal->status = WithdrawalRequest::REJECTED_STATUS;
                    $dropshipper->earningsWithdrawal += $withdrawal->amount;
                    $withdrawal->save();
                }
            }
        }
        $withdrawalAmount = WithdrawalRequest::where('dropshipper_id', $dropshipper->id)
            ->where('status', WithdrawalRequest::APPROVED_STATUS)
            ->whereNull('order_id')
            ->get();
        $Totalamount = 0;
        foreach($withdrawalAmount as $withdrawal)
        {
            $transactions = Transaction::where('dropshipper_id', $dropshipper->id)
                ->where('isStatus', ProfitEnum::AVAILABLE)
                ->get();
            $amount = $withdrawal->amount;
            $Totalamount += $withdrawal->amount;
            foreach($transactions as $transaction)
            {
                $newAmount = $amount - $transaction->profitRatio;
                if($newAmount >= 0)
                {
                    $this->updateTransaction($transaction, ProfitEnum::WITHDRAWAL_DONE, ProfitEnum::WITHDRAWAL,
                        $withdrawal->id);
                    $this->updateWithdrawal($withdrawal, $transaction->order_id);
                    $Totalamount -= $transaction->profitRatio;
                    $amount = $newAmount;
                }
            }
        }
        if($Totalamount > 0)
        {
            $transactions = Transaction::where('dropshipper_id', $dropshipper->id)
                ->where('isStatus', ProfitEnum::AVAILABLE)
                ->orderBy('profitRatio', 'desc')
                ->get();
            foreach($transactions as $transaction)
            {
                $newTotalamount = $Totalamount - $transaction->profitRatio;
                if($newTotalamount >= 0)
                {
                    $this->updateTransaction($transaction, ProfitEnum::WITHDRAWAL_DONE, ProfitEnum::WITHDRAWAL,
                        $withdrawal->id);
                    $Totalamount = $newTotalamount;
                }
            }
        }
        $grandTotal = Order::where('dropshipper_id', $dropshipper->id)
            ->where('paymentMethod', PaymentEnum::WALLET_METHOD_ID)
            ->whereNotIn('status_id', [OrderEnum::NEW_STATUS, OrderEnum::PAY_PENDING_STATUS,OrderEnum::PENDING_STATUS])
            ->sum('grandTotal');
        $grandTotal += $Totalamount;
        if($depositAmount >= $grandTotal)
        {
            $dropshipper->walletBalance -= $grandTotal;
        }else
        {
            $cost = ($grandTotal - $depositAmount);
            $dropshipper->walletBalance -= $depositAmount;
            $dropshipper->walletBalance -= $cost;
            $transactions = Transaction::where('dropshipper_id', $dropshipper->id)
                ->where('isStatus', ProfitEnum::AVAILABLE)
                ->orderBy('profitRatio', 'desc')
                ->pluck('profitRatio', 'id')->toArray();
            $resultKeys = [];
            if(count($transactions))
            {
                $resultKeys = $this->getSubsetSumToTarget($transactions, $cost);
            }
            if(count($resultKeys))
            {
                $transactions = Transaction::whereIn('id', $resultKeys)->get();
                foreach($transactions as $transaction)
                {
                    $cost -= $transaction->profitRatio;
                    $dropshipper->walletBalance += $transaction->profitRatio;
                    $this->updateTransaction($transaction, ProfitEnum::WALLETE_DONE, ProfitEnum::WALLETE);
                }
            }else
            {
                $transactions = Transaction::where('dropshipper_id', $dropshipper->id)
                    ->where('isStatus', ProfitEnum::AVAILABLE)
                    ->orderBy('profitRatio', 'desc')
                    ->get();
                foreach($transactions as $transaction)
                {
                    $newCost = $cost - $transaction->profitRatio;
                    if($newCost >= 0)
                    {
                        $dropshipper->walletBalance += $transaction->profitRatio;
                        $this->updateTransaction($transaction, ProfitEnum::WALLETE_DONE, ProfitEnum::WALLETE);
                        $cost = $newCost;
                    }
                }
            }
            if($cost > 0)
            {
                $transactions = Transaction::where('dropshipper_id', $dropshipper->id)
                    ->where('isStatus', ProfitEnum::AVAILABLE)
                    ->orderBy('profitRatio', 'desc')->get();
                foreach($transactions as $transaction)
                {
                    $newCost = $cost - $transaction->profitRatio;
                    if($newCost < 0)
                    {
                        $dropshipper->walletBalance += $transaction->profitRatio;
                        $this->updateTransaction($transaction, ProfitEnum::WALLETE_DONE, ProfitEnum::WALLETE);
                        $cost = $newCost;
                        break;
                    }
                }
            }
            if($dropshipper->walletBalance < 0)
            {
                $dropshipper->earningsWithdrawal += $dropshipper->walletBalance;
                $dropshipper->walletBalance = 0;
            }
        }
        $dropshipper->earningsWithdrawal -= $grandTotal;
        $dropshipper->save();
    }

    // Method for updating transaction
    private function updateTransaction($transaction, $status, $type, $withdrawal_request_id = null)
    {
        try
        {
            $transaction->isStatus = $status;
            $transaction->earning_type = $type;
            $transaction->earning_date = Carbon::now();
            $transaction->withdrawal_request_id = $withdrawal_request_id;
            $transaction->save();
        }catch(\Exception $e)
        {
            $this->error("Error updating transaction ID {$transaction->id}: " . $e->getMessage());
        }
    }

    private function updateWithdrawal($withdrawal, $order_id = null, $amount = null, $check = true)
    {
        try
        {
            if($withdrawal->order_id != null)
            {
                $withdrawal->order_id = json_encode(array_merge(json_decode($withdrawal->order_id),
                    [$order_id]));
            }else
            {
                $withdrawal->order_id = json_encode([$order_id]);
            }
            $withdrawal->status = $withdrawal->status;
            $withdrawal->save();
        }catch(\Exception $e)
        {
            $this->error("Error updating withdrawal ID {$withdrawal->id}: " . $e->getMessage());
        }
    }

    private function getSubsetSumToTarget($array, $target)
    {
        if(!count($array))
        {
            return [];
        }
        $arrayKeys = array_keys($array); // Get array keys
        $totalKeys = count($arrayKeys);  // Number of elements in the array
        // Loop through all possible subsets using bitmasking
        $totalSubsets = 1 << $totalKeys; // 2^n subsets
        for($i = 0; $i < $totalSubsets; $i++)
        {
            $sum = 0;
            $subsetKeys = [];
            // Check which elements are included in this subset
            for($j = 0; $j < $totalKeys; $j++)
            {
                if($i & (1 << $j))
                {
                    $sum += $array[$arrayKeys[$j]]; // Add the value
                    $subsetKeys[] = $arrayKeys[$j]; // Store the key
                }
            }
            // Check if the sum equals the target
            if($sum === $target)
            {
                return $subsetKeys; // Return the keys that form the subset
            }
        }
        return []; // No subset sums to the target
    }
}
