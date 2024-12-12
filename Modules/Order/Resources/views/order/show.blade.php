@extends($layout)

@section('title', 'Order Details')

@push('styles')
    <style>
        .swal2-container .swal2-html-container {
            max-height: 500px !important;
        }
    </style>
@endpush
@section('content')
    <!--begin::Main-->
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar  py-4 py-lg-8 ">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container  container-fluid d-flex flex-stack flex-wrap ">
                    <!--begin::Toolbar wrapper-->
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <!--begin::Page title-->
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <!--begin::Title-->
                            <h1
                                class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                                Order Details (#{{ $data->id }})
                            </h1>
                            <!--end::Title-->
                            <!--begin::Breadcrumb-->
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    <a href="/metronic8/demo31/index.html" class="text-muted text-hover-primary">
                                        Home
                                    </a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    Order Logistics
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-gray-900">
                                    {{ $data->id }}
                                </li>
                                <!--end::Item-->
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            @if (permissionShow('view_SubStatus'))
                                @if ($data->subStatus)
                                    <span class="badge badge-info">Substatus: {{ $data->subStatus->name }}</span>
                                @endif

                                @if ($data->remark)
                                    <span class="badge badge-info">Remark: {{ $data->remark->name }}</span>
                                @endif
                            @endif

                            <button class="btn btn-success">
                                {{ getStatusText($data->status?->name?->value) }}
                            </button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Toolbar wrapper-->
                </div>
                <!--end::Toolbar container-->
            </div>

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
                                    <li class="nav-item">
                                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                            href="#kt_ecommerce_wms">WMS</a>
                                    </li>
                                    <!--end:::Tab item-->
                                @endif
                                @if ($data->notes()->count())
                                    <!--begin:::Tab item-->
                                    <li class="nav-item">
                                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                            href="#kt_ecommerce_notes">Notes</a>
                                    </li>
                                    <!--end:::Tab item-->
                                @endif
                            </ul>
                            <!--end:::Tabs-->

                            <div>
                                @if (in_array($data->status_id, [getStatusId(\Modules\Order\Enums\OrderEnum::NEW_STATUS)]))
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
                                @if ($invoice && $invoice->pdf_link)
                                    <a href="{{ url('/api/invoice/download/' . $data->id) }}" target="blank"
                                        class="btn btn-flex btn-danger h-40px fs-7 fw-bold mx-2">
                                        Invoice PDF
                                    </a>
                                @endif

                                <a class="btn btn-flex btn-primary h-40px fs-7 fw-bold mx-2"
                                    href="{{ route('order.showLogs', $data) }}">
                                    Order Logs
                                    <i class="fas fa-history" style="margin-left:5px;"></i>
                                </a>
                                @permission('update_order')
                                    @if ($data->status_id != \Modules\Order\Enums\OrderEnum::COMPLETED_STATUS)
                                        <a class="btn btn-flex btn-info h-40px fs-7 fw-bold"
                                            href="{{ route('order.followUp.show', $data) }}">
                                            Follow-Up
                                            <i class="fas fa-phone" style="margin-left:5px;"></i>
                                        </a>
                                    @else
                                        <span class="btn btn-flex btn-info h-40px fs-7 fw-bold">
                                            No Follow-Up Available
                                            <i class="fas fa-phone" style="margin-left:5px;"></i>
                                        </span>
                                    @endif
                                @endpermission

                            </div>
                        </div>

                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                                <!--begin::Orders-->
                                <div class="d-flex flex-column gap-7 gap-lg-10">
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
                                                    <table
                                                        class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                                        <tbody class="fw-semibold text-gray-600">
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="ki-outline ki-calendar fs-2 me-2"></i>Date
                                                                        Added
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">
                                                                    {{ $data->created_at->format('Y-m-d') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-outline ki-wallet fs-2 me-2"></i>Payment
                                                                        Method
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">
                                                                    {{ isset($data->paymentMethodData) ? $data->paymentMethodData['name'] : null }}
                                                                    @if ($data->paymentMethod == 1)
                                                                        <img src="{{ asset('dashboard') }}/assets/media/svg/card-logos/visa.svg"
                                                                            class="w-50px ms-2" />
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @if ($data->status_id == \Modules\Order\Enums\OrderEnum::COMPLETED_STATUS)
                                                                <tr>
                                                                    <td class="text-muted">
                                                                        <div class="d-flex align-items-center">
                                                                            <i
                                                                                class="ki-outline ki-wallet fs-2 me-2"></i>Earning
                                                                            Status
                                                                        </div>
                                                                    </td>
                                                                    <td class="fw-bold text-end">
                                                                        {{ $data->transaction->isStatus ? \Modules\Finance\Enums\ProfitEnum::status($data->transaction->isStatus, $data->transaction) : null }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if ($data->tracking_number)
                                                                <tr>
                                                                    <td class="text-muted">
                                                                        <div class="d-flex align-items-center">
                                                                            <i
                                                                                class="ki-outline ki-truck fs-2 me-2"></i>Shipping
                                                                            Method
                                                                        </div>
                                                                    </td>
                                                                    <td class="fw-bold text-end">Aymakan</td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-outline ki-social-media fs-2 me-2"></i>Source
                                                                        Platform
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">{{ $data->source_platform }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-outline ki-social-media fs-2 me-2"></i>Created
                                                                        Platform
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">{{ $data->created_platform }}
                                                                    @if ($data->is_import)
                                                                        ( sheet )
                                                                    @endif
                                                                </td>
                                                            </tr>
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
                                                    <table
                                                        class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                                        <tbody class="fw-semibold text-gray-600">
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-outline ki-profile-circle fs-2 me-2"></i>Customer
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-end">

                                                                        <!--begin::Name-->
                                                                        <p class="text-gray-600 text-hover-primary">
                                                                            {{ $data->customerName }}</p>
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
                                                                <td class="fw-bold text-end">{{ $data->customerPhone }}
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

                                    </div>
                                    <!--end::Order summary-->
                                    <!--begin::Customer details-->
                                    <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
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
                                                    <table
                                                        class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                                        <tbody class="fw-semibold text-gray-600">
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-outline ki-profile-circle fs-2 me-2"></i>ID
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-end">
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
                                                                    <a
                                                                        href="{{ route('dropshipper.show', $data->dropshipper_id) }}">{{ $data->dropshipper->first_name . ' ' . $data->dropshipper->last_name }}</a>
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
                                        <div class="card card-flush py-4 flex-row-fluid">
                                            <!--begin::Card header-->
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h2>Validated Details</h2>
                                                </div>
                                            </div>
                                            <!--end::Card header-->
                                            <!--begin::Card body-->
                                            <div class="card-body pt-0">
                                                <div class="table-responsive">
                                                    <!--begin::Table-->
                                                    <table
                                                        class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                                        <tbody class="fw-semibold text-gray-600">
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-outline ki-profile-circle fs-2 me-2"></i>Assign
                                                                        to
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-end">
                                                                        <!--begin::Name-->
                                                                        {{ $data->operator->name ?? '' }}
                                                                        <!--end::Name-->
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @if ($data->validated_at)
                                                                <tr>
                                                                    <td class="text-muted">
                                                                        <div class="d-flex align-items-center">
                                                                            <i
                                                                                class="ki-outline ki-profile-circle fs-2 me-2"></i>validated
                                                                            By
                                                                        </div>
                                                                    </td>
                                                                    <td class="fw-bold text-end">
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-end">
                                                                            <!--begin::Name-->
                                                                            {{ $data->validationOperator->name ? $data->validationOperator->name : (in_array($data->ollops_confirmation_status, ['confirmed', 'cancelled']) ? 'ollops' : 'system') }}
                                                                            <!--end::Name-->
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <td class="text-muted">
                                                                        <div class="d-flex align-items-center">
                                                                            <i
                                                                                class="ki-outline ki-profile-circle fs-2 me-2"></i>validated
                                                                            By
                                                                        </div>
                                                                    </td>
                                                                    <td class="fw-bold text-end">
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-end">
                                                                            <!--begin::Name-->
                                                                            <!--end::Name-->
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-outline ki-profile-circle fs-2 me-2"></i>validated
                                                                        type
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-end">
                                                                        <!--begin::Name-->
                                                                        {{ $data->validated_by }}
                                                                        <!--end::Name-->
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-outline ki-profile-circle fs-2 me-2"></i>ollops
                                                                        status
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-end">
                                                                        <!--begin::Name-->
                                                                        {{ $data->ollops_confirmation_status }}
                                                                        <!--end::Name-->
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="ki-outline ki-profile-circle fs-2 me-2"></i>sent
                                                                        to ollops
                                                                    </div>
                                                                </td>
                                                                <td class="fw-bold text-end">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-end">
                                                                        <!--begin::Name-->
                                                                        {{ $data->sent_to_ollops_at }}
                                                                        <!--end::Name-->
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                            </div>
                                            <!--end::Card body-->
                                        </div>
                                    </div>
                                    <!--end::Customer details-->
                                    <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">

                                        <!--begin::Shipping address-->
                                        <div class="card card-flush py-4 flex-row-fluid position-relative">
                                            <!--begin::Background-->
                                            <div
                                                class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
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
                                                <br /><b>Street: </b> {{ $data->customerAddress }}
                                                @if ($data->district)
                                                    <br /><b>District: </b> {{ $data->district }}
                                                @endif
                                                <br /><b>City: </b> {{ $data->city?->name?->value }}
                                                <br /><b>Country: </b> {{ $data->country?->name?->value }}
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
                                                        <tr
                                                            class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="min-w-175px">Product</th>
                                                            <th class="min-w-100px text-end">SKU</th>
                                                            <th class="min-w-70px text-end">Qty</th>
                                                            <th class="min-w-100px text-end">Unit Price</th>
                                                            <th class="min-w-100px text-end">Selling Price</th>
                                                            <th class="min-w-100px text-end">Dropshipper net profit</th>
                                                            <th class="min-w-100px text-end">Total</th>
                                                            <th class="min-w-100px text-end">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-600">
                                                        @foreach ($data->orderItems as $item)
                                                            @if (json_decode($item->product_details))
                                                                @php $product_details = json_decode($item->product_details)[0]; @endphp
                                                            @else
                                                                @php $product_details = $item->product; @endphp
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
                                                                    {{ $dataObject->sku ?? $item->product?->sku }}</td>
                                                                <td class="text-end">{{ $item->quantity }}</td>
                                                                <td class="text-end">
                                                                    {{ round($product_details->cost_price, 2) }}
                                                                    SAR
                                                                </td>
                                                                <td class="text-end">{{ round($item->unitPrice, 2) }} SAR
                                                                </td>
                                                                <td class="text-end">{{ $item->net_profit }} SAR</td>
                                                                <td class="text-end">{{ $item->totalPrice }} SAR</td>
                                                                <td class="text-end"
                                                                    style="color: {{ getStatusColor($item->status_id) }}">
                                                                    {{ getStatusSupplierName($item->status_id) }}</td>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td colspan="4" class="text-end">Subtotal (Vat included)</td>
                                                            <td class="text-end">{{ $data->subTotal }} SAR</td>
                                                        </tr>

                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td colspan="4" class="text-end">Shipping Fee</td>
                                                            <td class="text-end">{{ $data->shippingFees }} SAR</td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td colspan="4" class="fs-3 text-dark text-end">Grand Total
                                                            </td>
                                                            <td class="text-dark fs-3 fw-bolder text-end">
                                                                {{ $data->grandTotal }} SAR
                                                            </td>
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
                                <div class="d-flex flex-row gap-7 gap-lg-10 row">
                                    <!--begin::Order history-->
                                    <div class="card card-flush py-4 flex-row-fluid col-8">
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

                                    <div class="col-3">
                                        <timeline :order_id="{{ $data->id }}" allow="false"></timeline>
                                    </div>

                                </div>
                                <!--end::Orders-->
                            </div>
                            <!--end::Tab pane-->

                            @if ($data->tracking_number)
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
                                                                <th class="min-w-175px">Reason Code</th>
                                                                <th class="min-w-175px">Reason Description</th>
                                                                <th class="min-w-175px">Requested Delivery Date</th>
                                                                <th class="min-w-70px">Created At</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">
                                                            @foreach ($data->OrderStatusAymakan as $row)
                                                                <tr>
                                                                    <td>{{ $row['status'] }}</td>
                                                                    <td>{{ $row['description'] }}</td>
                                                                    <td>{{ $row['tracking'] }}</td>
                                                                    <td>{{ $row['reason_code'] }}</td>
                                                                    <td>{{ $row['reason_description'] }}</td>
                                                                    <td>{{ $row['requested_delivery_date'] }}</td>
                                                                    <td>{{ $row['created_at'] }}</td>
                                                                </tr>
                                                            @endforeach


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!--end::Card body-->
                                        </div>
                                        <!--end::Order history-->

                                    </div>
                                    <!--end::Orders-->
                                </div>
                                <div class="tab-pane fade" id="kt_ecommerce_wms" role="tab-panel">
                                    <!--begin::Orders-->
                                    <div class="d-flex flex-column gap-7 gap-lg-10">
                                        <!--begin::Order history-->
                                        <div class="card card-flush py-4 flex-row-fluid">
                                            <!--begin::Card header-->
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h2>Order WMS</h2>
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
                                                                <th class="min-w-100px">Status</th>
                                                                <th class="min-w-70px">Created At</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">
                                                            @foreach ($data->wms_status as $row)
                                                                <tr>
                                                                    <td>{{ $row->status }}</td>
                                                                    <td>{{ $row->created_at }}</td>
                                                                </tr>
                                                            @endforeach


                                                        </tbody>
                                                    </table>
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
                            @if ($data->notes()->count())
                                <div class="tab-pane fade" id="kt_ecommerce_notes" role="tab-panel">
                                    <!--begin::Orders-->
                                    <div class="d-flex flex-column gap-7 gap-lg-10">
                                        <!--begin::Order history-->
                                        <div class="card card-flush py-4 flex-row-fluid">
                                            <!--begin::Card header-->
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h2>Order Notes</h2>
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
                                                                <th class="min-w-100px">Content</th>
                                                                <th class="min-w-70px">Created At</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fw-semibold text-gray-600">
                                                            @forelse ($data->notes as $note)
                                                                <tr>
                                                                    <td>{{ $note['content'] }}</td>
                                                                    <td>{{ $note['created_at'] }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td rowspan="2">No notes</td>
                                                                </tr>
                                                            @endforelse


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!--end::Card body-->
                                        </div>
                                        <!--end::Order history-->

                                    </div>
                                    <!--end::Orders-->
                                </div>
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

    </div>

@endsection

@section('second-sidebar')
    @include('order::layouts.sidebar')
@endsection

@push('scripts')
    <script>
        var orderId = "{{ $data->id }}"
        var currentButton = null
    </script>
    <script src="{{ asset('dashboard2/assets/js/order/orderStatus.js') }}"></script>
@endpush
