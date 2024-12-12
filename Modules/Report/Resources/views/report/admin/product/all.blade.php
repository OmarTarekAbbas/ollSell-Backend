@extends($layout)
@section('content')
    @push('styles')
        <style>
            .card-click:hover {
                -webkit-filter: brightness(50%);
                -webkit-transition: all 1s ease;
                -moz-transition: all 1s ease;
                -o-transition: all 1s ease;
                -ms-transition: all 1s ease;
                transition: all 1s ease;
                cursor: pointer;
            }
        </style>
    @endpush
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                {{-- <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->

                </div> --}}
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <div class="row" data-kt-user-table-filter="form">
                    <!--begin::Input group-->
                    <div class="col-md-6">
                        <label class="form-label fs-6 fw-semibold">Sku:</label>
                        <select id="product" name="product" onchange="getFilter()"
                                class="form-select form-select-solid form-select-lg fw-semibold"
                                data-mce-placeholder="">
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-semibold">Dropshipper:</label>
                        <select id="dropshipper" name="dropshipper[]" onchange="getFilter()"
                                class="form-select form-select-solid form-select-lg fw-semibold" data-mce-placeholder=""
                                multiple> </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-semibold">Supplier:</label>
                        <select id="supplier" name="supplier[]" onchange="getFilter()"
                                class="form-select form-select-solid form-select-lg fw-semibold" data-mce-placeholder=""
                                multiple> </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-semibold">Warehouse:</label>
                        <select id="warehouse" name="warehouse[]"
                                class="form-select form-select-solid form-select-lg fw-semibold"
                                onchange="getFilter()" class="form-select" multiple></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-semibold">Date:</label>
                        <div class="mb-10 d-flex justify-content-between">
                            <select id="period_type" class="form-select" data-control="select2" onchange="getFilter()"
                                    data-placeholder="Select an option" name="period">
                                <option></option>
                                <option value="today" @if($request->period == 'today') selected @endif>Today</option>
                                <option value="this_week" @if($request->period == 'this_week') selected @endif>This
                                    Week
                                </option>
                                <option value="this_month" @if($request->period == 'this_month') selected @endif>This
                                    Month
                                </option>
                                <option value="this_year" @if($request->period == 'this_year') selected @endif>This
                                    Year
                                </option>
                                <option value="custom" @if($request->period == 'custom') selected @endif>Custom</option>
                            </select>
                        </div>

                        <div id="date_range" class="mb-10 d-flex justify-content-between d-none">
                            <label class="form-label fs-6 fw-semibold">From:</label>

                            <input type="text" name="fromDate" id="fromDate" autocomplete="off" value=""
                                   style="margin-right:5px" class="form-select"/>
                            <label class="form-label fs-6 fw-semibold">To:</label>

                            <input type="text" name="toDate" id="toDate" autocomplete="off" value=""
                                   style="margin-right:5px" class="form-select"/>
                        </div>
                    </div>
                    <!--end::Input group-->
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end ">
                            <a href="{{ route('dashboard.report.product.all', ['period' => 'this_month']) }}"
                               id="reset_filter" class="btn btn-light btn-active-danger fw-semibold me-2 px-6"
                               data-kt-user-table-filter="reset">Reset
                            </a>

                        </div>
                    </div>
                </div>


            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->


    </div>
    <!--end::Products-->
    <br>
    <div class="card-body pt-0" id="data-table">
        @include('report::report.admin.product.mainContent')
    </div>
    </div>
    </div>
@endsection
@section('second-sidebar')
    @include('report::layouts.admin.sidebar')
@endsection
@push('scripts')
    <script>
        let csrfToken = "{{ csrf_token() }}";

        $(function () {

            $('input[name="fromDate"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD'));
                getFilter()
            });
            $('input[name="toDate"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD'));
                getFilter()
            });

            $('input[name="fromDate"]').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                // defaultValue: null,
                locale: {
                    format: 'YYYY/MM/DD',
                }
            });
            $('input[name="toDate"]').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY/MM/DD',
                }
            });
            $('input[name="fromDate"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
            $('input[name="toDate"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });


        function getFilter() {
            var params = {
                period: $("#period_type").val() == null ? "{{ $request->period ?? 'this_month' }}" : $("#period_type").val(),
                is_report: 1,
                warehouse_id: $("#warehouse").val(),
                supplier_id: $("#supplier").val(),
                product_id: $("#product").val(),
                dropshipper_id: $("#dropshipper").val(),
                toDate: toDate,
                fromDate: fromDate,
            };
            if ($("#fromDate").val()) {
                params.fromDate = $("#fromDate").val();
            }
            if ($("#toDate").val()) {
                params.toDate = $("#toDate").val();
            }
            var routeAll =
                "{{ request()->fullUrl() == request()->url() ? request()->url() . '?' : request()->url() . '?' }}" +
                jQuery.param(params);
            $('#data-table').html(
                '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
            );
            $.get({
                url: routeAll,
                success: function (data) {
                    jQuery(document).ready(function () {
                        $('#data-table').html(data);
                    });
                },
                error: function (textStatus) {
                    var div = `<div class="alert alert-danger text-center mt-5 mb-5 p-5">` + textStatus.responseJSON.message + `</div>`
                    $('#data-table').html(div);

                }
            });
        }

        $(document).ready(function () {
            $("#period_type").on('change', function () {
                if (this.value == "custom") {
                    $("#date_range").removeClass('d-none');
                } else {
                    $("#date_range").addClass('d-none');
                }
            });

            $('#warehouse').select2({
                placeholder: "{{ 'select warehouse' }}...",
                ajax: {
                    url: "{{ route('warehouse.list') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                }
                            }),
                        };
                    },
                }
            });
            $('#supplier').select2({
                placeholder: "{{ 'select supplier' }}...",
                ajax: {
                    url: "{{ route('supplier.list') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                }
                            }),
                        };
                    },
                }
            });
            $('#dropshipper').select2({
                placeholder: "{{ 'select dropshipper' }}...",
                ajax: {
                    url: "{{ route('dropshipper.search') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.email,
                                }
                            }),
                        };
                    },
                }
            });
            $('#product').select2({
                placeholder: "{{ 'select sku' }}...",
                ajax: {
                    url: "{{ route('product.search') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name.value
                                }
                            }),
                        };
                    },
                }
            });
        });

        @can('view_product')
        function getProduct() {
            var params = {
                toDate: toDate,
                fromDate: fromDate,
                is_report: 1
            };
            if ($("#product").val()) {
                params.product_id = $("#product").val();
            }
            if ($("#warehouse").val()) {
                params.warehouse_id = $("#warehouse").val();
            }
            if ($("#supplier").val()) {
                params.supplier_id = $("#supplier").val();
            }
            if ($("#dropshipper").val()) {
                params.dropshipper_id = $("#dropshipper").val();
            }
            window.open("{{ route('product.index') }}?" + jQuery.param(params), "_blank");
        }

        function getLiveProduct(status) {
            var params = {
                toDate: toDate,
                fromDate: fromDate,
                is_report: 1,
                status: status
            };
            if ($("#product").val()) {
                params.product_id = $("#product").val();
            }
            if ($("#warehouse").val()) {
                params.warehouse_id = $("#warehouse").val();
            }
            if ($("#supplier").val()) {
                params.supplier_id = $("#supplier").val();
            }
            if ($("#dropshipper").val()) {
                params.dropshipper_id = $("#dropshipper").val();
            }
            window.open("{{ route('product.index') }}?" + jQuery.param(params), "_blank");

        }
        @endcan
        @can('view_suppliers')
        function getSupplier() {
            var params = {
                toDate: toDate,
                fromDate: fromDate,
                is_report: 1
            };
            if ($("#product").val()) {
                params.product_id = $("#product").val();
            }
            if ($("#warehouse").val()) {
                params.warehouse_id = $("#warehouse").val();
            }
            if ($("#supplier").val()) {
                params.supplier_id = $("#supplier").val();
            }
            if ($("#dropshipper").val()) {
                params.dropshipper_id = $("#dropshipper").val();
            }
            window.open("{{ route('suppliers.index') }}?" + jQuery.param(params), "_blank");

        }
        @endcan
        @can('view_warehouse')
        function getWarehouse() {
            var params = {
                toDate: toDate,
                fromDate: fromDate,
                is_report: 1
            };
            if ($("#product").val()) {
                params.product_id = $("#product").val();
            }
            if ($("#warehouse").val()) {
                params.warehouse_id = $("#warehouse").val();
            }
            if ($("#supplier").val()) {
                params.supplier_id = $("#supplier").val();
            }
            if ($("#dropshipper").val()) {
                params.dropshipper_id = $("#dropshipper").val();
            }
            window.open("{{ route('warehouse.index') }}?" + jQuery.param(params), "_blank");
        }
        @endcan
        @can('view_dropshipper')
        function getDropshipper() {
            var params = {
                toDate: toDate,
                fromDate: fromDate,
                is_report: 1
            };
            if ($("#product").val()) {
                params.product_id = $("#product").val();
            }
            if ($("#warehouse").val()) {
                params.warehouse_id = $("#warehouse").val();
            }
            if ($("#supplier").val()) {
                params.supplier_id = $("#supplier").val();
            }
            if ($("#dropshipper").val()) {
                params.dropshipper_id = $("#dropshipper").val();
            }
            window.open("{{ route('dropshipper.index') }}?" + jQuery.param(params), "_blank");
        }
        @endcan
        @can('view_order')
        function getOrder(status) {
            var params = {
                toDate: toDate,
                fromDate: fromDate,
                is_report: 1,
            };
            if (status) {
                params.status_id = status;
            }
            if ($("#product").val()) {
                params.product_id = $("#product").val();
            }
            if ($("#warehouse").val()) {
                params.warehouse_id = $("#warehouse").val();
            }
            if ($("#supplier").val()) {
                params.supplier_id = $("#supplier").val();
            }
            if ($("#dropshipper").val()) {
                params.dropshipper_id = $("#dropshipper").val();
            }
            window.open("{{ route('order.Logistics') }}?" + jQuery.param(params), "_blank");
        }
        @endcan
    </script>
@endpush
