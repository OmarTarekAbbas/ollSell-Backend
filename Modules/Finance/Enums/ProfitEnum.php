<?php

namespace Modules\Finance\Enums;

use Carbon\Carbon;

enum ProfitEnum: int
{
    /**
     * Payment methods list
     *
     * @case string
     */
    const PENDING = 0;

    const AVAILABLE = 1;

    const REFUNDED = 2;

    const WITHDRAWAL_DONE = 3;
    const WITHDRAWAL_PENDING = 4;
    const WALLETE_DONE = 5;

    const WITHDRAWAL = 'withdrawal';
    const WALLETE = 'wallete';

    public static function status($case, $transaction): string
    {

        switch ($case) {
            case self::PENDING:
                return trans('orders.pending transaction', ['date' => Carbon::parse($transaction->created_at)->addDay(7)->format('d/m/Y'), 'id' => $transaction->withdrawal_request_id]);
            case self::AVAILABLE:
                return trans('orders.available transaction');
            case self::REFUNDED:
                return trans('orders.refunded transaction', ['date' => Carbon::parse($transaction->earning_date)->format('d/m/Y')]);
            case self::WITHDRAWAL_DONE:
                return trans('orders.withdrawal done transaction', ['date' => Carbon::parse($transaction->earning_date)->format('d/m/Y'), 'id' => $transaction->withdrawal_request_id]);
            case self::WITHDRAWAL_PENDING:
                return trans('orders.withdrawal pending transaction', ['id' => $transaction->withdrawal_request_id]);
            case self::WALLETE_DONE:
                return trans('orders.wallete transaction', ['date' => Carbon::parse($transaction->earning_date)->format('d/m/Y')]);
            default:
                return '';
        }
    }
}
