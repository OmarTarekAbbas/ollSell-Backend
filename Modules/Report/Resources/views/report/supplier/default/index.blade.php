@extends($layout)

@section('content')
<div id="kt_app_content_container" class="app-container container-fluid">
    <div class="row gy-5 g-xl-10">
        <!--begin::Col-->
        <div class="col-sm-6 col-xl-3 mb-xl-10">

            <!--begin::Card widget 2-->
            <div class="card h-lg-100">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <i class="ki-outline ki-compass fs-2hx text-gray-600"></i>

                    </div>
                    <!--end::Icon-->

                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-7">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">{{ $productsCount }}</span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="m-0">
                            <span class="fw-semibold fs-6 text-gray-400">
                                Products </span>
                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->

                </div>
                <!--end::Body-->
            </div>
            <!--end::Card widget 2-->


        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-sm-6 col-xl-3 mb-xl-10">

            <!--begin::Card widget 2-->
            <div class="card h-lg-100">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <i class="ki-outline ki-map fs-2hx text-gray-600"></i>
                    </div>
                    <!--end::Icon-->

                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-7">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">{{ $warehousesCount }}</span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="m-0">
                            <span class="fw-semibold fs-6 text-gray-400">
                                Warehouses </span>

                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->

                </div>
                <!--end::Body-->
            </div>
            <!--end::Card widget 2-->


        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-sm-6 col-xl-3 mb-xl-10">

            <!--begin::Card widget 2-->
            <div class="card h-lg-100">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <i class="ki-outline ki-abstract-39 fs-2hx text-gray-600"></i>

                    </div>
                    <!--end::Icon-->

                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-7">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">{{ $totalRevenue }}</span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="m-0">
                            <span class="fw-semibold fs-6 text-gray-400">
                                Total Revenue </span>

                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->

                </div>
                <!--end::Body-->
            </div>
            <!--end::Card widget 2-->


        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-sm-6 col-xl-3 mb-xl-10">

            <!--begin::Card widget 2-->
            <div class="card h-lg-100">
                <!--begin::Body-->
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <i class="ki-outline ki-chart-simple fs-2hx text-gray-600"></i>
                    </div>
                    <!--end::Icon-->

                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-7">
                        <!--begin::Number-->
                        <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">{{ $totalProductsSold }}</span>
                        <!--end::Number-->

                        <!--begin::Follower-->
                        <div class="m-0">
                            <span class="fw-semibold fs-6 text-gray-400">
                                Total Products Sold </span>

                        </div>
                        <!--end::Follower-->
                    </div>
                    <!--end::Section-->

                </div>
                <!--end::Body-->
            </div>
            <!--end::Card widget 2-->


        </div>
        <!--end::Col-->

    </div>

    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">


        <!--begin::Col-->
        <div class="col-xxl-12">
            <div id="kt_app_content_container">
                <!--begin::Products-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h3>Top Products</h3>

                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">

                        <!--begin::Table-->
                        <div id="kt_ecommerce_report_sales_table_wrapper"
                            class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                    id="kt_ecommerce_report_sales_table">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-100px sorting" tabindex="0"
                                                aria-controls="kt_ecommerce_report_sales_table" rowspan="1" colspan="1"
                                                style="width: 205.133px;"
                                                aria-label="Product">Product</th>
                                            <th class="text-end min-w-75px sorting" tabindex="0"
                                                aria-controls="kt_ecommerce_report_sales_table" rowspan="1" colspan="1"
                                                style="width: 161.167px;"
                                                aria-label="No. Orders: activate to sort column ascending">No. Orders
                                            </th>
                                            <th class="text-end min-w-75px sorting" tabindex="0"
                                                aria-controls="kt_ecommerce_report_sales_table" rowspan="1" colspan="1"
                                                style="width: 222.667px;"
                                                aria-label="Products Sold: activate to sort column ascending">Product
                                                Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @foreach($topProducts as $topProduct)
                                        <tr class="odd">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Thumbnail-->
                                                    <!-- <a href="{{ route('supplier.product.edit', $topProduct['product']->id) }}" class="symbol symbol-50px">
                                                        <span class="symbol-label" style="background-image:url(/metronic8/demo31/assets/media//stock/ecommerce/45.png);"></span>
                                                    </a> -->
                                                    <!--end::Thumbnail-->

                                                    <div class="ms-5">
                                                        <!--begin::Title-->
                                                        <a href="{{ route('supplier.product.edit', $topProduct['product']->id) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">
                                                        {{ Str::limit($topProduct['product']->name->value, 30) }}
                                                        </a>
                                                        <!--end::Title-->
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0">
                                                {{ $topProduct['total_orders'] }}
                                            </td>
                                            <td class="text-end pe-0">
                                                {{ $topProduct['total_revenue'] }} SAR
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Products-->
            </div>

        </div>
        <!--end::Col-->
    </div>


</div>
@endsection

@section('second-sidebar')
<!--begin::Environment-->

<!--begin::Body-->
<div class="card-body">
    <!--begin::Item-->
    <div class="d-flex flex-stack">
        <!--begin::Symbol-->
        <div class="symbol symbol-40px me-4">
            <div class="symbol-label fs-2 fw-semibold bg-danger text-inverse-danger">W</div>
        </div>
        <!--end::Symbol-->
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{ route('supplier.warehouse.index') }}"
                    class="text-gray-800 text-hover-primary fs-6 fw-bold">Warehouses</a>
                <span class="text-muted fw-semibold d-block fs-7">all warehouses</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{ route('supplier.warehouse.index') }}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                {{ $warehousesCount }}
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    <!--end::Item-->
    <!--begin::Separator-->
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->

    <!--begin::Separator-->
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->
    <!--begin::Item-->
    <div class="d-flex flex-stack">
        <!--begin::Symbol-->
        <div class="symbol symbol-40px me-4">
            <div class="symbol-label fs-2 fw-semibold bg-info text-inverse-info">P</div>
        </div>
        <!--end::Symbol-->
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{ route('supplier.product.index') }}"
                    class="text-gray-800 text-hover-primary fs-6 fw-bold">Products</a>
                <span class="text-muted fw-semibold d-block fs-7">all products</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{ route('supplier.product.index') }}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                {{ $productsCount }}
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    <!--end::Item-->

</div>
<!--end::Body-->
<!--end::Environment-->
@endsection

@push('styles')
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush