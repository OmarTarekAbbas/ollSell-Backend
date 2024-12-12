<div class="row">
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderPendingQuantityCount)}} / {{number_format($orderPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total pending quantity / order</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderPendingInventoryQuantityCount)}} / {{number_format($orderPendingInventoryCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total pending inventory quantity / order</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($onHoldOrderQuantityCount)}} / {{number_format($onHoldOrderCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total On Hold Quantity  / order fc</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($returnOrderQuantityCount)}} / {{number_format($returnOrderCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Return Quantity / order </span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
</div>
<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_product_table">
    <thead>
    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
        <th>{{ __('id') }}</th>
        <th>{{ __('Product') }}</th>
        <th>{{ __('SKU') }}</th>
        <th>{{ __('QTY') }}</th>
        <th style="width:100px;
word-wrap: break-word;">{{ __('pending order quantity / order') }}</th>
        <th style="width:100px;
word-wrap: break-word;">{{ __('pending inventory quantity / order') }}</th>
        <th style="width:100px;
word-wrap: break-word;">{{ __('on hold order quantity / order') }}</th>
        <th style="width:100px;
word-wrap: break-word;">{{ __('Return quantity / order') }}</th>
    </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
    @forelse($products as $product)
        @php
            $cq = $product->orderItems()->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->where('status_id',\Modules\Order\Enums\OrderEnum::PENDING_STATUS)->sum('quantity');
            $cqi = $product->orderItems()->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->where('status_id',\Modules\Order\Enums\OrderEnum::PENDING_INVENTORY_STATUS)->sum('quantity');
            $onc = $product->orderItems()->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->where('status_id',\Modules\Order\Enums\OrderEnum::PREPARING_STATUS)->whereHas('order', function($query)
        {
            $query->whereHas('wms_status', function($quy)
            {
                $quy->where('status', 'on_hold')
                    ->whereRaw('wms_order_status.status = (SELECT MAX(op.status) FROM wms_order_status op WHERE op.order_id = wms_order_status.order_id)');
            });
        })->sum('quantity');
            $rc = $product->orderItems()->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->whereIn('status_id',[\Modules\Order\Enums\OrderEnum::SHIPPING_STATUS,\Modules\Order\Enums\OrderEnum::REJECTED_STATUS])->whereHas('order', function($query)
        {
                $query->whereHas('OrderStatusAymakan', function($quy)
                {
                    $quy->whereIn('status', ['AY-0059','AY-0028','AY-0084'])
                        ->whereRaw('order_statuses_aymakan.status = (SELECT MAX(op.status) FROM order_statuses_aymakan op WHERE op.order_id = order_statuses_aymakan.order_id)');
                })->whereHas('wms_status', function($quy)
                {
                    $quy->where('status', 'shipped')
                        ->whereRaw('wms_order_status.status = (SELECT MAX(op.status) FROM wms_order_status op WHERE op.order_id = wms_order_status.order_id)');
                });
            })->sum('quantity');
        @endphp
        <tr @if($product->quantity == 0) style="background-color: #f8b3b3"
            @elseif($cq >= $product->quantity) style="background-color: rgb(18 235 255);" @endif>
            <td><p>{{ $product->id }}</p></td>

            <td>
                <div class="d-flex align-items-center">
                    <input type="hidden" name="item_check" value="{{ $product->id }}"/>
                    <!--begin::Thumbnail-->
                    <!--end::Thumbnail-->
                    <div class="ms-5" style="width:100px;
word-wrap: break-word;">
                        <!--begin::Title-->
                        <p class="text-gray-800 text-hover-primary fs-5 fw-bold"
                           data-kt-ecommerce-product-filter="product_name">{{ $product->name->value}}</p>
                        <!--end::Title-->
                    </div>
                </div>
            </td>
            <td><p>{{ $product->sku }}</p></td>
            <td><p>{{ $product->quantity }}</p></td>
            <td><p>{{ $cq }}
                    / {{ $product->orderItems()->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->where('status_id',\Modules\Order\Enums\OrderEnum::PENDING_STATUS)->count()}}</p>
            </td>
            <td>
                <p>{{ $cqi }}
                    / {{ $product->orderItems()->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->where('status_id',\Modules\Order\Enums\OrderEnum::PENDING_INVENTORY_STATUS)->count()}}</p>
            </td>
            <td><p>{{ $onc }} / {{ $product->orderItems()->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->where('status_id',\Modules\Order\Enums\OrderEnum::PREPARING_STATUS)->whereHas('order', function($query)
        {
            $query->whereHas('wms_status', function($quy)
            {
                $quy->where('status', 'on_hold')
                    ->whereRaw('wms_order_status.status = (SELECT MAX(op.status) FROM wms_order_status op WHERE op.order_id = wms_order_status.order_id)');
            });
        })->count()}}</p>
            </td>
            <td><p>{{ $rc }} / {{ $product->orderItems()->whereBetween('created_at', [$currentPeriod['from'], $currentPeriod['to']])->whereIn('status_id',[\Modules\Order\Enums\OrderEnum::SHIPPING_STATUS,\Modules\Order\Enums\OrderEnum::REJECTED_STATUS])->whereHas('order', function($query)
        {
                $query->whereHas('OrderStatusAymakan', function($quy)
                {
                    $quy->whereIn('status', ['AY-0059','AY-0028','AY-0008','AY-0084'])
                        ->whereRaw('order_statuses_aymakan.status = (SELECT MAX(op.status) FROM order_statuses_aymakan op WHERE op.order_id = order_statuses_aymakan.order_id)');
                })->whereHas('wms_status', function($quy)
                {
                    $quy->where('status', 'shipped')
                        ->whereRaw('wms_order_status.status = (SELECT MAX(op.status) FROM wms_order_status op WHERE op.order_id = wms_order_status.order_id)');
                });
            })->count()}}</p>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7">
                <div class="alert alert-danger text-center"><h3 class="text-center text-gray">No Records to
                        display...</h3></div>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
