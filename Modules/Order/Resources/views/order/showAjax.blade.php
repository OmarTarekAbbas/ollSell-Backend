<div class="modal-header">
    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
        Order Details ({{ $data->id }})
    </h1>

    @if (permissionShow('view_SubStatus'))
        @if ($data->subStatus)
            <span class="badge badge-info mx-2">Substatus: {{ $data->subStatus->name }}</span>
        @endif

        @if ($data->remark)
            <span class="badge badge-info mx-2">Remark: {{ $data->remark->name }}</span>
        @endif
    @endif

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body" style="padding-bottom:0 !important">
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">

            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid" style="padding-bottom:0;">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <!--begin::Order details page-->
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
                            <!--begin:::Tabs-->
                            <ul
                                    class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
                                <!--begin:::Tab item-->
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                       href="#kt_ecommerce_sales_order_summary">Order Summary</a>
                                </li>
                                <!--end:::Tab item-->
                                <!--begin:::Tab item-->
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                       href="#kt_ecommerce_sales_order_history">Order History</a>
                                </li>
                                <!--end:::Tab item-->

                                @if ($data->tracking_number)
                                    <!--begin:::Tab item-->
                                    <li class="nav-item">
                                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                           href="#kt_ecommerce_shipping">Shipping</a>
                                    </li>
                                    <!--end:::Tab item-->
                                @endif

                                <!--begin:::Tab item-->
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                        href="#kt_ecommerce_sales_followUps">Follow Up</a>
                                </li>
                                <!--end:::Tab item-->
                            </ul>
                            <!--end:::Tabs-->

                            <div>
                                @if (in_array($data->status_id, [getStatusId($data::NEW_STATUS)]))
                                    @permission('update_order')
                                    <a href="{{ route('order.editOrder', $data) }}" class="btn btn-info">
                                        <i class="ki-outline ki-wrench fs-2"></i>
                                        Edit Order
                                    </a>
                                    @endpermission
                                @endif

                                @if ($data->pdf_label)
                                    <a href="{{ $data->pdf_label }}" target="blank" class="btn btn-warning">
                                        Shipping Info
                                    </a>
                                @endif


                                <a class="btn btn-flex btn-primary h-40px fs-7 fw-bold"
                                   href="{{ route('order.showLogs', $data) }}" target="_blank">
                                    Order Logs
                                    <i class="fas fa-history" style="margin-left:5px;"></i>
                                </a>

                                @if (permissionShow('update_SubStatus'))
                                    <button class="btn btn-success" id="changeStatusBtn"
                                            data-order-id="{{ $data->id }}">
                                        {{ getStatusText($data->status?->name?->value) }}
                                        <i class="fas fa-gear" style="margin-left:5px;"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary"
                                 role="tab-panel">
                                <!--begin::Orders-->
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <!--begin::Order summary-->
                                    <div class="d-flex flex-column gap-7 gap-lg-5">
                                        {{-- Basic details --}}
                                        <div class="card card-dashed h-xl-100 flex-row-fluid flex-wrap p-6">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column py-2">
                                                <!--begin::Owner-->
                                                <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                                    Basic Details
                                                    <span
                                                        class="badge badge-light-success fs-7 ms-2">{{ $data->id }}</span>
                                                </div>
                                                <!--end::Owner-->

                                                <!--begin::Wrapper-->
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Icon-->

                                                    <i class="fa-solid fa-list"
                                                        style="font-size: 2rem;padding: 10px 20px;"></i>
                                                    <!--end::Icon-->

                                                    <!--begin::Details-->
                                                    <div>

                                                        <div class="fs-6 fw-semibold text-gray-700">Payment Method:
                                                            {{ $data->paymentMethodData['name'] }}</div>
                                                        <div class="fs-6 fw-semibold text-gray-700">Date Added:
                                                            {{ $data->created_at->format('Y-m-d') }}</div>
                                                        @if ($data->tracking_number)
                                                            <div class="fs-6 fw-semibold text-gray-700">Shipping Method:
                                                                Aymakan</div>

                                                        @endif
                                                    </div>
                                                    <!--end::Details-->
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        {{-- End basic details --}}
                                        {{-- Customer Details --}}
                                        <div class="card card-dashed h-xl-100 flex-row-fluid flex-wrap p-6">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column py-2">
                                                <!--begin::Owner-->
                                                <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                                    Customer Details
                                                </div>
                                                <!--end::Owner-->

                                                <!--begin::Wrapper-->
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Icon-->

                                                    <i class="fa-solid fa-users"
                                                        style="font-size: 2rem;padding: 10px 20px;"></i>
                                                    <!--end::Icon-->

                                                    <!--begin::Details-->
                                                    <div>

                                                        <div class="fs-6 fw-semibold text-gray-700">Customer name:
                                                            {{ $data->customerName }}</div>
                                                        <div class="fs-6 fw-semibold text-gray-700">Phone:
                                                            {{ $data->customerPhone }}</div>
                                                    </div>
                                                    <!--end::Details-->
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        {{-- End customer details  --}}

                                        {{-- Dropshipper details  --}}
                                        <div class="card card-dashed h-xl-100 flex-row-fluid flex-wrap p-6">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column py-2">
                                                <!--begin::Owner-->
                                                <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                                    Dropshipper Details
                                                </div>
                                                <!--end::Owner-->

                                                <!--begin::Wrapper-->
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Icon-->

                                                    <i class="fa-solid fa-user"

                                                        style="font-size: 2rem;padding: 10px 20px;"></i>
                                                    <!--end::Icon-->

                                                    <!--begin::Details-->
                                                    <div>
                                                        <div class="fs-6 fw-semibold text-gray-700">
                                                            Name:
                                                            <a
                                                                href="{{ route('dropshipper.show', $data->dropshipper_id) }}">
                                                                {{ $data->dropshipper->first_name . ' ' . $data->dropshipper->last_name }}
                                                            </a>
                                                        </div>

                                                        <div class="fs-6 fw-semibold text-gray-700">Email:
                                                            {{ $data->dropshipper->email }}</div>
                                                    </div>
                                                    <!--end::Details-->
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        {{-- End dropshipper details --}}

                                        {{-- Address details  --}}
                                        <div class="card card-dashed h-xl-100 flex-row-fluid flex-wrap p-6">
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column py-2">
                                                <!--begin::Owner-->
                                                <div class="d-flex align-items-center fs-4 fw-bold mb-5">
                                                    Address Details
                                                </div>
                                                <!--end::Owner-->

                                                <!--begin::Wrapper-->
                                                <div class="d-flex align-items-center">

                                                    <!--begin::Icon-->
                                                    <i class="fa-solid fa-shipping-fast"

                                                        style="font-size: 2rem;padding: 10px 20px;"></i>
                                                    <!--end::Icon-->

                                                    <!--begin::Details-->
                                                    <div>


                                                        <div class="fs-6 fw-semibold text-gray-700">Street:
                                                            {{ $data->customerAddress }}</div>
                                                        @if ($data->district)
                                                            <div class="fs-6 fw-semibold text-gray-700">District:
                                                                {{ $data->district }}</div>
                                                        @endif
                                                        <div class="fs-6 fw-semibold text-gray-700">City:
                                                            {{ $data->city?->name?->value }}</div>
                                                        <div class="fs-6 fw-semibold text-gray-700">Country:
                                                            {{ $data->country?->name?->value }}</div>
                                                    </div>

                                                    <!--end::Details-->
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        {{-- End dropshipper details --}}
                                    </div>
                                    <!--end::Order summary-->

                                    <!--begin::Product List-->
                                    <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Order #{{ $data->id }}</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <div class="table-responsive">
                                                <!--begin::Table-->
                                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                    <thead>
                                                    <tr
                                                            class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-175px">Product</th>
                                                        <th class="min-w-100px text-end">SKU</th>
                                                        <th class="min-w-70px text-end">Qty</th>
                                                        <th class="min-w-100px text-end">Unit Price</th>
                                                        <th class="min-w-100px text-end">Selling Price</th>
                                                        <th class="min-w-100px text-end">Dropshipper net profit
                                                        </th>
                                                        <th class="min-w-100px text-end">Total</th>
                                                        <th class="min-w-100px text-end">Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600">
                                                    @foreach ($data->orderItems as $item)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                        <?php $dataObject = json_decode($item->product_json); ?>
                                                                            <!--begin::Title-->
                                                                    <div class="ms-5">
                                                                        <a href="{{ route('product.edit', $item->product->id) }}"
                                                                           class="fw-bold text-gray-600 text-hover-primary">{{ $dataObject->product_name ?? $item->product?->name?->value }}</a>
                                                                        <!-- <div class="fs-7 text-muted">Delivery Date: 22/03/2023</div> -->
                                                                    </div>
                                                                    <!--end::Title-->
                                                                </div>
                                                            </td>
                                                            <td class="text-end">
                                                                {{ $dataObject->sku ?? $item->product?->sku }}</td>
                                                            <td class="text-end">{{ $item->quantity }}</td>
                                                            <td class="text-end">
                                                                {{ $item->product->price_after_discount ?? $item->product->cost_price }}
                                                                SAR
                                                            </td>
                                                            <td class="text-end">{{ $item->unitPrice }} SAR</td>
                                                            <td class="text-end">{{ $item->net_profit }} SAR</td>
                                                            <td class="text-end">{{ $item->totalPrice }} SAR</td>
                                                            <td class="text-end"
                                                                style="color: {{ getStatusColor($item->status_id) }}">
                                                                {{ getStatusSupplierName($item->status_id) }}</td>
                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <td>Subtotal</td>
                                                        <td class="text-end">{{ $data->subTotal }} SAR</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <!-- <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td colspan="4" class="text-end">VAT (0%)</td>
                                                                                    <td class="text-end">$0.00</td>
                                                                                </tr> -->
                                                    <tr>
                                                        <td>Shipping Rate</td>
                                                        <td class="text-end">{{ $data->shippingFees }} SAR</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fs-3 text-dark">Grand
                                                            Total
                                                        </td>
                                                        <td class="text-dark fs-3 fw-bolder text-end">
                                                            {{ $data->grandTotal }} SAR
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!--end::Table-->
                                            </div>
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Product List-->
                                </div>
                                <!--end::Orders-->
                            </div>
                            <!--end::Tab pane-->
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">
                                <!--begin::Orders-->
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <!--begin::Order history-->
                                    <div class="card card-flush py-4 flex-row-fluid">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Order History</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <div class="table-responsive">
                                                <!--begin::Table-->
                                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                    <thead>
                                                    <tr
                                                            class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-175px">Date Added</th>
                                                        <th class="min-w-175px">Status</th>
                                                        <th class="min-w-70px">Description</th>
                                                        <th class="min-w-100px">Customer Notifed</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600">
                                                    @foreach ($data->orderStatus as $row)
                                                        <tr>
                                                            <td>{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                                            <td>
                                                                <!--begin::Badges-->
                                                                <div class="badge badge-light-success">
                                                                    {{ $row->statusTitle($row->status_id) }}
                                                                </div>
                                                                <!--end::Badges-->
                                                            </td>
                                                            <td>
                                                                {{ $row->statusDescription($row->status_id) }}
                                                            </td>
                                                            <td>Yes</td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                                <!--end::Table-->
                                            </div>
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Order history-->

                                </div>
                                <!--end::Orders-->
                            </div>
                            <!--end::Tab pane-->

                            @if ($data->tracking_number && $shipments)
                                <!--begin::Tab pane-->
                                <div class="tab-pane fade" id="kt_ecommerce_shipping" role="tab-panel">
                                    <!--begin::Orders-->
                                    <div class="d-flex flex-column gap-7 gap-lg-10">
                                        <!--begin::Order history-->
                                        <div class="card card-flush py-4 flex-row-fluid">
                                            <!--begin::Card header-->
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h2>Order Shipping</h2>
                                                </div>
                                            </div>
                                            <!--end::Card header-->
                                            <!--begin::Card body-->
                                            <div class="card-body pt-0">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                        <thead>
                                                        <tr
                                                                class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="min-w-100px">Status code</th>
                                                            <th class="min-w-175px">Description</th>
                                                            <th class="min-w-175px">Tracking</th>
                                                            <th class="min-w-70px">Created At</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">
                                                        @foreach ($data->OrderStatusAymakan as $row)
                                                            <tr>
                                                                <td>{{ $row['status'] }}</td>
                                                                <td>{{ $row['description'] }}</td>
                                                                <td>{{ $row['tracking'] }}</td>
                                                                <td>{{ $row['created_at'] }}</td>
                                                            </tr>
                                                        @endforeach


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Order history-->
                                </div>
                                <!--end::Orders-->
                            @endif




                            <!--begin::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_sales_followUps" role="tab-panel">
                                <!--begin::Orders-->
                                <div class="d-flex flex-column gap-7 gap-lg-10 py-4">
                                    @if (count($data->followUps) > 0)
                                        @foreach ($data->followUps as $index => $followUp)
                                            <div class="timeline-item">
                                                <!-- Badge -->
                                                <div class="timeline-badge mr-6">
                                                    <i class="activity-icon "></i>
                                                </div>

                                                <!-- Content -->
                                                <div class="timeline-content d-flex flex-column">
                                                    <!-- Label with activity date -->
                                                    <div class="timeline-label fw-bold text-gray-800 fs-6">
                                                        <span>
                                                            {{ $followUp->created_at->format('Y-m-d H:i:s') }}
                                                            <!-- Assuming $followUp->created_at is a Carbon instance -->
                                                        </span>
                                                    </div>

                                                    <!-- Display title if available -->
                                                    <span class="fw-bold text-gray-800 ps-3">
                                                        {{ $followUp->activity_type }}
                                                    </span>

                                                    <!-- Display content -->
                                                    <span
                                                        class="fw-mormal timeline-content text-muted ps-3 text-gray-800">
                                                        {{ $followUp->content }}
                                                    </span>
                                                    <span
                                                        class="fw-mormal timeline-content text-muted ps-3 d-flex flex-row text-gray-500">
                                                        By: {{ $followUp->user->name }} ({{ $followUp->user->email }})
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div>No Follow-Up</div>
                                    @endif
                                </div>
                                <!--end::Orders-->
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    <!--end::Tab content-->
                </div>
                <!--end::Order details page-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

</div>
<script>
    var orderId = "{{ $data->id }}"
    var currentButton = null
</script>
