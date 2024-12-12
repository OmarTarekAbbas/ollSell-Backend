<?php

namespace Modules\Order\Actions\Order;

use Illuminate\Http\Request;
use Modules\Order\Repositories\OrderRepository;

class GetExportedOrdersAction
{
    protected $filters;
    protected $query;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function execute()
    {
        return $this->getOrders($this->filters);
    }

    public function applyFiltersFromRequest($filters)
    {
        $result = [];
        $parts = explode(",", $filters);

        foreach ($parts as $part) {
            if (!$part) continue;
            [$key, $value] = explode("_", $part);
            $result[$key] = $value;
        }

        return $result;
    }

    public function applyFiltersToQuery($filters)
    {
        if (isset($filters['fromSubTotal']) && isset($filters['toSubTotal'])) {
            // Apply filtering for subTotal
            if ($filters['fromSubTotal'] && $filters['toSubTotal']) {
                $this->query->whereBetween('subTotal', [$filters['fromSubTotal'], $filters['toSubTotal']]);
            } else {
                if ($filters['fromSubTotal']) {
                    $this->query->where('subTotal', '>=', $filters['fromSubTotal']);
                }
                if ($filters['toSubTotal']) {
                    $this->query->where('subTotal', '<=', $filters['toSubTotal']);
                }
            }
        }

        if (isset($filters['fromGrandTotla']) && isset($filters['toGrandTotla'])) {
            // Apply filtering for grandTotal
            if ($filters['fromGrandTotla'] && $filters['toGrandTotla']) {
                $this->query->whereBetween('grandTotal', [$filters['fromGrandTotla'], $filters['toGrandTotla']]);
            } else {
                if ($filters['fromGrandTotla']) {
                    $this->query->where('grandTotal', '>=', $filters['fromGrandTotla']);
                }
                if ($filters['toGrandTotla']) {
                    $this->query->where('grandTotal', '<=', $filters['toGrandTotla']);
                }
            }
        }

        if (isset($filters['fromDate']) && isset($filters['toDate'])) {
            // Apply filtering for dates
            if ($filters['fromDate'] && $filters['toDate']) {
                $toDate = date('Y-m-d', strtotime($filters['toDate'] . ' +1 day'));
                $this->query->whereBetween('created_at', [$filters['fromDate'], $toDate]);
            } else {
                if ($filters['fromDate']) {
                    $this->query->where('created_at', '>=', $filters['fromDate']);
                }
                if ($filters['toDate']) {
                    $toDate = date('Y-m-d', strtotime($filters['toDate'] . ' +1 day'));
                    $this->query->where('created_at', '<=', $toDate);
                }
            }
        }

        // Apply similar logic for other filters like statusId, paymentMethod, deliveryDate, etc.

        return $this->query;
    }

    public function getOrders($filters)
    {
        $parts = explode(",", $filters);
        $result = [];

        $moreConditionForFirstLevel = [];
        $recursiveRel = [];

        try {

            foreach ($parts as $part) {
                // Split each pair into key and value
                [$key, $value] = explode("_", $part);
                // Remove any whitespace from the key
                $key = trim($key);
                // Add the key-value pair to the result array
                $result[$key] = $value;
            }

            if ($result['fromSubTotal'] && $result['toSubTotal']) {

                $moreConditionForFirstLevel += ['whereBetween' => ['subTotal' => [$result['fromSubTotal'], $result['toSubTotal']]]];
            } else {

                if ($result['fromSubTotal']) $moreConditionForFirstLevel += ['where' => ['subTotal' => ['>=', $result['fromSubTotal']]]];

                if ($result['toSubTotal']) $moreConditionForFirstLevel += ['where' => ['subTotal' => ['<=', $result['toSubTotal']]]];
            }

            if ($result['fromGrandTotla'] && $result['toGrandTotla']) {

                $moreConditionForFirstLevel += ['whereBetween' => ['grandTotal' => [$result['fromGrandTotla'], $result['toGrandTotla']]]];
            } else {

                if ($result['fromGrandTotla']) $moreConditionForFirstLevel += ['where' => ['grandTotal' => ['>=', $result['fromGrandTotla']]]];

                if ($result['toGrandTotla']) $moreConditionForFirstLevel += ['where' => ['grandTotal' => ['<=', $result['toGrandTotla']]]];
            }

            if ($result['fromDate'] && $result['toDate']) {
                // Add 1 day to the "to" date to include records on that day
                $toDate = date('Y-m-d', strtotime($result['toDate'] . ' +1 day'));
                $moreConditionForFirstLevel += ['whereBetween' => ['created_at' => [$result['fromDate'], $toDate]]];
            } else {
                if ($result['fromDate']) $moreConditionForFirstLevel += ['where' => ['created_at' => ['>=', $result['fromDate']]]];
                if ($result['toDate']) {
                    // Add 1 day to the "to" date to include records on that day
                    $toDate = date('Y-m-d', strtotime($result['toDate'] . ' +1 day'));
                    $moreConditionForFirstLevel += ['where' => ['created_at' => ['<=', $toDate]]];
                }
            }

            if ($result['fromDeliveryDate'] && $result['toDeliveryDate']) {
                // Add 1 day to the "to" delivery date to include records on that day
                $toDeliveryDate = date('Y-m-d', strtotime($result['toDeliveryDate'] . ' +1 day'));
                $moreConditionForFirstLevel += ['whereBetween' => ['deliveryDate' => [$result['fromDeliveryDate'], $toDeliveryDate]]];
            } else {
                if ($result['fromDeliveryDate']) $moreConditionForFirstLevel += ['where' => ['deliveryDate' => ['>=', $result['fromDeliveryDate']]]];
                if ($result['toDeliveryDate']) {
                    // Add 1 day to the "to" delivery date to include records on that day
                    $toDeliveryDate = date('Y-m-d', strtotime($result['toDeliveryDate'] . ' +1 day'));
                    $moreConditionForFirstLevel += ['where' => ['deliveryDate' => ['<=', $toDeliveryDate]]];
                }
            }

            if ($result['statusId'] && is_numeric($result['statusId'])) {

                $moreConditionForFirstLevel += ['where' => ['status_id' => [$result['statusId']]]];
            }

            if ($result['paymentMethod'] && is_numeric($result['paymentMethod'])) {

                $moreConditionForFirstLevel += ['where' => ['paymentMethod' => [$result['paymentMethod']]]];
            }

            if ($result['search'] && strlen($result['search']) < 100) {

                $searchValue = $result['search'];

                if (is_numeric($searchValue)) {

                    if (strlen($searchValue) > 6) {

                        $moreConditionForFirstLevel += ['where' => ['customerPhone' => [$searchValue]]];
                    } else {

                        $moreConditionForFirstLevel += ['where' => ['id' => [$searchValue]]];
                    }
                } else {
                    $moreConditionForFirstLevel += ['where' => ['customerName' => ['LIKE', '%' . $searchValue . '%']]];
                    $recursiveRel += ['dropshipper' => ['type' => 'orWhereHas', 'orWhere' => [
                        'email' => ['orWhere' => $searchValue],
                        'store_name' => ['orWhere' => $searchValue],
                        'first_name' => ['orWhere' => $searchValue],
                        'second_name' => ['orWhere' => $searchValue],
                        'email' => ['orWhere' => $searchValue],
                        'phone' => ['orWhere' => $searchValue],
                        'bankAccountName' => ['orWhere' => $searchValue],
                    ]]];
                }
            }
        } catch (\Throwable $th) {
        }

        return App(OrderRepository::class)->all(
            request()->all(),
            withRelations: ['dropshipper', 'status' => ['translation'],'notes'],
            moreConditionForFirstLevel: $moreConditionForFirstLevel,
            isDatatable: false,
            orderBy: ['column' => 'id', 'order' => 'desc'],
            pagination: false,
        );
    }
}
