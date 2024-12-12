@extends($layout)

@section('content')
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
            <!--begin::Filter-->
            <button type="button" class="btn btn-primary me-3 dropdown-toggle" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                <span class="svg-icon svg-icon-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                            fill="currentColor"/>
                    </svg>
                </span>
                <!--end::Svg Icon-->Filter
            </button>
            <!--begin::Menu 1-->
            <form method="GET"  id="filterDataForm" action="#"
                  class="dropdown-menu w-300px w-md-325px" data-kt-menu="true" aria-labelledby="dropdownMenuClickable">
                <!--begin::Header-->
                <div class="px-7 py-5">
                    <div class="fs-5 text-dark fw-bold">Filter Options</div>
                </div>
                <!--end::Header-->
                <!--begin::Separator-->
                <div class="separator border-gray-200"></div>
                <!--end::Separator-->
                <!--begin::Content-->
                <div class="px-7 py-5" data-kt-user-table-filter="form">
                    <div class="mb-10 d-flex justify-content-between">
                        <select id="period_type" class="form-select" data-control="select2" data-placeholder="Select an option" name="period">
                            <option></option>
                            <option value="today">Today</option>
                            <option value="this_week">This Week</option>
                            <option value="this_month">This Month</option>
                            <option value="this_year">This Year</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                    <div id="date_range" class="mb-10 d-flex justify-content-between d-none">
                        <div class="mx-2">
                            <label class="form-label fs-6 fw-semibold">From:</label>
                            <input
                                type="text"
                                name="fromDate"
                                id="fromDate"
                                autocomplete="off"
                                value=""

                                style="margin-right:5px"
                                class="form-select"
                            />
                        </div>
                        <div>
                            <label class="form-label fs-6 fw-semibold">To:</label>

                            <input
                                type="text"
                                name="toDate"
                                id="toDate"
                                autocomplete="off"
                                value=""
                                style="margin-right:5px"
                                class="form-select"
                            />
                        </div>
                    </div>


                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <a  href="{{route('dashboard.report.default', ['period' => 'this_month'])}}" id="reset_filter"
                                class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                data-kt-user-table-filter="reset">Reset
                        </a>
                        <!-- <button type="reset" id="reset_filter" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-user-table-filter="reset">Reset</button> -->
                        <button type="submit" class="btn btn-primary fw-semibold px-6 filterDataForm"
                                data-kt-menu-dismiss="true"  data-kt-user-table-filter="filter">Apply
                        </button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Content-->
            </form>


            <!--end::Filter-->


            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->


        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="//www.google.com/jsapi"></script>
        <script src="{{ asset('dashboard') }}/assets/js/custom/documentation/charts/google-charts/line.js"></script>
        <script src="{{ asset('dashboard') }}/assets/js/custom/documentation/charts/google-charts/line.js"></script>


        <div class="card-body pt-0" id="data-table">
            @include('report::report.admin.default.mainContent')
        </div>

    </div>
    <!--end::Products-->

</div>
</div>
@endsection

@section('second-sidebar')
@include('report::layouts.admin.sidebar')
@endsection

@push('scripts')
<script>
    let csrfToken = "{{ csrf_token() }}";

    $(function() {

        $('input[name="fromDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD'));
        });
        $('input[name="toDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD'));
        });

        $('input[name="fromDate"]').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false,
            // defaultValue: null,
            locale: {
                format: 'YYYY/MM/DD',
            }
        });

        $('input[name="fromDate"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('input[name="toDate"]').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY/MM/DD',
            }
        });

        $('input[name="toDate"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

    });

    $(".filterDataForm").on("click", function(e) {
        e.preventDefault();
        var routeAll =
            "{{ request()->fullUrl() == request()->url() ? request()->url() . '?' : request()->url() . '?' }}" +
            $("#filterDataForm").serialize();
        $('#data-table').html(
            '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
        );
        $('#filterDataForm').removeClass('show');
        $.get({
            url: routeAll,
            success: function(data) {
                jQuery(document).ready(function() {
                    $('#data-table').html(data);
                });
            },
            error: function (textStatus, errorThrown) {
                var div = `<div class="alert alert-danger text-center mt-5 mb-5 p-5">`+textStatus.responseJSON.message+`</div>`
                $('#data-table').html(div);
            }
        });
    });
    $(document).ready(function() {
        $("#period_type").on('change', function() {
            if (this.value == "custom") {
                $("#date_range").removeClass('d-none');
            } else {
                $("#date_range").addClass('d-none');
            }
        });
    });
</script>

@endpush
