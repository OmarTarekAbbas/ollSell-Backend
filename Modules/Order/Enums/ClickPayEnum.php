<?php

namespace Modules\Order\Enums;

enum ClickPayEnum
{
    /**
     * Payment methods list
     *
     * @case string
     */
    const Open = 'O'; // The user opened the payment gateway but did not complete the transaction.
    const Pay = 'A'; // Payment was successfully completed.
    const Try = 'T'; // The user is attempting to make the payment again.
    const Error = 'E'; // There was an issue during payment, such as incorrect data or network problems.
    const Failed = 'D'; // The transaction failed due to an issue.
    const Duplicate = 'R'; // The payment request was sent multiple times within the same minute.

}
