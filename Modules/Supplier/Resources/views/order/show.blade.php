@extends($layout)


@section('title', 'Order Details')

@section('content')

    <!--begin::Main-->
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">

            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
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
                            </ul>
                            <!--end:::Tabs-->

                            @if ($data->tracking_number)
                                <div>
                                    <a href="{{ $data->pdf_label }}" target="blank" class="btn btn-warning">Shipping
                                        Info</a>
                                </div>
                            @endif

                        </div>
                    </div>
                    <!--begin::Order summary-->
                    <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                        <!--begin::Order details-->
                        <div class="card card-flush py-4 flex-row-fluid">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Order Details (#{{ $data->id }})</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                        <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-calendar fs-2 me-2"></i>Date Added
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $data->created_at->format('Y-m-d') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-wallet fs-2 me-2"></i>Payment Method
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $data->paymentMethodData['name'] }}
                                                @if ($data->paymentMethod == 1)
                                                    <img src="{{ asset('dashboard') }}/assets/media/svg/card-logos/visa.svg"
                                                         class="w-50px ms-2"/>
                                            </td>
                                            @endif
                                        </tr>
                                        @if ($data->tracking_number)
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ki-outline ki-truck fs-2 me-2"></i>Shipping Method
                                                    </div>
                                                </td>
                                                <td class="fw-bold text-end">Aymakan</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Order details-->
                        <!--begin::Customer details-->
                        <div class="card card-flush py-4 flex-row-fluid">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Customer Details</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                        <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-profile-circle fs-2 me-2"></i>Customer
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">
                                                <div class="d-flex align-items-center justify-content-end">

                                                    <!--begin::Name-->
                                                    <p class="text-gray-600 text-hover-primary">
                                                        {{ $data->customerName }}
                                                    </p>
                                                    <!--end::Name-->
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                                    <td class="text-muted">
                                                        <div class="d-flex align-items-center">
                                                        <i class="ki-outline ki-sms fs-2 me-2"></i>Email</div>
                                                    </td>
                                                    <td class="fw-bold text-end">
                                                        <a href="#" class="text-gray-600 text-hover-primary">{{ $data->dropshipper->email }}</a>
                                                    </td>
                                                </tr> -->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-phone fs-2 me-2"></i>Phone
                                                </div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $data->customerPhone }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Customer details-->

                    </div>
                    <!--end::Order summary-->
                    <!--begin::Customer details-->
                    <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Dropshipper Details</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-profile-circle fs-2 me-2"></i>ID
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <!--begin::Name-->
                                                <a href="{{ route('dropshipper.show', $data->dropshipper_id) }}"
                                                   class="text-hover-primary">{{ $data->dropshipper_id }}</a>
                                                <!--end::Name-->
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-phone fs-2 me-2"></i>Name
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            <a href="{{ route('dropshipper.show', $data->dropshipper_id) }}">{{ $data->dropshipper->first_name . ' ' . $data->dropshipper->last_name }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-sms fs-2 me-2"></i>Email
                                            </div>
                                        </td>
                                        <td class="fw-bold text-end">
                                            <a href="{{ route('dropshipper.show', $data->dropshipper_id) }}"
                                               class="text-hover-primary">{{ $data->dropshipper->email }}</a>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Customer details-->
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                            <!--begin::Orders-->
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">

                                    <!--begin::Shipping address-->
                                    <div class="card card-flush py-4 flex-row-fluid position-relative">
                                        <!--begin::Background-->
                                        <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                                            <i class="ki-solid ki-delivery" style="font-size: 13em"></i>
                                        </div>
                                        <!--end::Background-->
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Address Details</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <br/><b>Street: </b> {{ $data->customerAddress }}
                                            @if ($data->district)
                                                <br/><b>District: </b> {{ $data->district }}
                                            @endif
                                            <br/><b>City: </b> {{ $data->city?->name?->value }}
                                            <br/><b>Country: </b> {{ $data->country?->name?->value }}
                                            <!--end::Card body-->
                                        </div>
                                    </div>
                                    <!--end::Shipping address-->
                                </div>
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
                                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="min-w-175px">Product</th>
                                                    <th class="min-w-100px text-end">SKU</th>
                                                    <th class="min-w-70px text-end">Qty</th>
                                                    <th class="min-w-100px text-end">Selling Price</th>
                                                    <th class="min-w-100px text-end">Total</th>
                                                    <th class="min-w-100px text-end">Status</th>
                                                    <th class="min-w-100px text-end">Action</th>
                                                </tr>
                                                </thead>
                                                <form action="{{ route('supplier.order.update_order_item', [$data]) }}"
                                                      method="post">
                                                    <tbody class="fw-semibold text-gray-600">
                                                    @foreach ($data->orderItems as $item)
                                                        @csrf
                                                        @if ($item->supplier_id != auth()->id())
                                                            @continue
                                                        @endif

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
                                                                {{ $dataObject->sku ?? $item->product?->sku }}
                                                            </td>
                                                            <td class="text-end">{{ $item->quantity }}</td>

                                                            <td class="text-end">{{ $item->unitPrice }} SAR</td>

                                                            <td class="text-end">{{ $item->totalPrice }} SAR</td>
                                                            <td class="text-end"
                                                                style="color: {{ getStatusColor($item->status_id) }}">
                                                                {{ getStatusSupplierName($item->status_id) }}
                                                            </td>


                                                            @if ($item->status_id == 13)
                                                                <td class="text-end">
                                                                    <input type="checkbox" class="ready-checkbox"
                                                                           onchange="updateCheckBoxByReady('{{$item->id}}')"
                                                                           name="is_ready" id="check-{{$item->id}}"
                                                                           @if($item->is_ready == 1) checked @endif >
                                                                </td>
                                                            @else
                                                                <td class="text-end">
                                                                    ----
                                                                </td>
                                                            @endif

                                                        </tr>
                                                    @endforeach
                                                    <?php
                                                    $totalPrice = [];
                                                    foreach($data->orderItems as $item)
                                                    {
                                                        if($item->supplier_id != auth()->id())
                                                        {
                                                            continue;
                                                        }
                                                        $totalPrice[] = $item->totalPrice;
                                                    }

                                                    $totalPriceSupplier = collect($totalPrice)->sum();
                                                    ?>


                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td colspan="4" class="fs-3 text-dark text-end">Grand
                                                            Total
                                                        </td>
                                                        <td class="text-dark fs-3 fw-bolder text-end">
                                                            {{ $totalPriceSupplier }} SAR
                                                        </td>
                                                    </tr>

                                                    <div>

                                                        @if($data->orderItems[0]->status_id == App\Enums\OrderEnum\OrderEnum::PREPARING_STATUS)
                                                            <button type="submit" class="btn btn-success disabled"
                                                                    id="ready-all-button">
                                                                Ready All
                                                            </button>
                                                        @endif
                                                    </div>
                                                    </tbody>
                                                </form>


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
                                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
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
                                            <div class="card-title  w-100">
                                                <h2 class="w-100" style="display:flex; justify-content:space-between">
                                                    Order Shipping
                                                    <span class="text-gray-600">Tracking number:
                                                    ({{ $shipments[0]['tracking_number'] }}) </span>
                                                </h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <div class="table-responsive">
                                                <!--begin::Table-->
                                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                    <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-100px">Status code</th>
                                                        <th class="min-w-175px">English Description</th>
                                                        <th class="min-w-175px">Arabic Description</th>
                                                        <th class="min-w-70px">Created At</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600">
                                                    @foreach (array_reverse($shipments[0]['tracking_info']) as $row)
                                                        <tr>
                                                            <td>{{ $row['status_code'] }}</td>
                                                            <td>{{ $row['description'] }}</td>
                                                            <td>{{ $row['description_ar'] }}</td>
                                                            <td>{{ $row['created_at'] }}</td>
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
                        @elseif($data->tracking_number)
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
                                                <p>Something went wrong while trying to fetch shipping data, try again
                                                    later.</p>
                                            </div>
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Order history-->

                                </div>
                                <!--end::Orders-->
                            </div>
                            <!--end::Tab pane-->
                        @endif
                    </div>
                    <!--end::Tab content-->
                </div>
                <!--end::Order details page-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->


    <!--end:::Main-->

@endsection
@section('second-sidebar')

    @include('supplier::layouts.sidebar')
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let allChecked = true;
            $('.ready-checkbox').each(function () {
                if (!this.checked) {
                    allChecked = false;
                }
            });

            if (allChecked) {
                $('#ready-all-button').removeClass('disabled');
            } else {
                $('#ready-all-button').addClass('disabled');
            }

            $('.ready-checkbox').change(function () {
                var allChecked = true;
                $('.ready-checkbox').each(function () {
                    if (!this.checked) {
                        allChecked = false;
                    }
                });

                if (allChecked) {
                    $('#ready-all-button').removeClass('disabled');
                } else {
                    $('#ready-all-button').addClass('disabled');
                }
            });

        });


        function updateCheckBoxByReady(id) {
            $.ajax({
                url: "{{route('supplier.order.update_checkBox_by_ready')}}",
                type: 'GET',
                data: {
                    'id': id,
                    'check': document.getElementById(`check-${id}`).checked
                },
                success: function (data) {
                    // Insert the received HTML into the checkbox-container
                    $('#checkbox-container').html(data);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    </script>

@endpush