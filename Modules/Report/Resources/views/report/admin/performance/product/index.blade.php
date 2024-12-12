@extends($layout)

@section('title', 'Products Capacity')

@section('content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
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
                                Products Capacity
                            </h1>
                            <!--end::Title-->

                            <!--begin::Breadcrumb-->
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ url('/') }}" class="text-muted text-hover-primary">
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
                                    Report
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item text-gray-900">
                                    Products Capacity
                                </li>
                                <!--end::Item-->

                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Toolbar wrapper-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                          rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                                    <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="currentColor"/>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->

                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 d-flex">
                         - <div class="w-100 mw-150px">
                                <!--begin::Select2-->
                                <button type="button" class="btn btn-primary me-3" data-kt-menu-trigger="click"
                                        data-kt-menu-placement="bottom-end">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                                    <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                        fill="currentColor"/>
                            </svg>
                        </span>
                                    <!--end::Svg Icon-->Filter
                                </button>
                                <!--begin::Menu 1-->
                                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                                     id="kt-toolbar-filter" data-popper-placement="bottom-end">
                                    <!--begin::Header-->
                                    <div class="px-7 py-5">
                                        <div class="fs-4 text-dark fw-bold">Filter Options</div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Separator-->
                                    <div class="separator border-gray-200"></div>
                                    <!--end::Separator-->
                                    <form action="{{ route('dashboard.report.performance.product.capaste', Request()->all()) }}" method="get">
                                        <!--begin::Content-->
                                        <div class="px-7 py-5">
                                            <!--begin::Input group-->
                                            <div class="mb-10">

                                                <!--begin::Label-->
                                                <label class="form-label fs-5 fw-semibold mb-3">Date:</label>
                                                <!--end::Label-->
                                                <input type="text" class="form-control"
                                                       name="period" hidden
                                                       value="thisCustom"
                                                >
                                                <!--begin::Options-->
                                                <div class="d-flex flex-column flex-wrap fw-semibold"
                                                     data-kt-product-table-filter="period">
                                                    <div class="d-flex flex-column flex-wrap fw-semibold"
                                                         data-kt-docs-table-filter="period">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <label for="exampleFormControlInput1"
                                                                           class="form-label">From</label>
                                                                    <input type="date" class="form-control"
                                                                           name="fromDate"
                                                                           value="{{ request('fromDate') ?? old('fromDate')}}"
                                                                           id="exampleFormControlInput1">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <label for="exampleFormControlTextarea1"
                                                                           class="form-label">To</label>
                                                                    <input type="date" class="form-control"
                                                                           name="toDate"
                                                                           value="{{ request('toDate') ?? old('toDate')}}"
                                                                           id="exampleFormControlTextarea1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label class="form-label fs-6 fw-semibold">Status:</label>
                                                <div class="mb-10 d-flex justify-content-between">
                                                    <select id="statusType" class="form-select" data-control="select2"
                                                            data-placeholder="Select an option" name="statusType">
                                                        <option></option>
                                                        <option value="on_hold">on hold
                                                        </option>
                                                        <option value="return" >
                                                           return
                                                        </option>
                                                        <option value="pending"
                                                                >pending
                                                        </option>
                                                        <option value="pending_inventory" >
                                                          pending inventory
                                                        </option>
                                                    </select>
                                                </div>
                                                <!--end::Options-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('dashboard.report.performance.product.capaste') }}"
                                                   id="reset_filter"
                                                   class="btn btn-light btn-active-danger fw-semibold me-2 px-6"
                                                   data-kt-user-table-filter="reset">Reset
                                                </a>
                                                <button type="submit" class="btn btn-primary"
                                                        data-kt-menu-dismiss="true">Apply
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </div>
                                    </form>
                                    <!--end::Content-->
                                </div>
                                <!--end::Select2-->
                            </div>
                            <!--begin::Add product-->

                            <!--end::Add product-->
                        </div>
                    </div>
                    <!--end::Card header-->
                </div>
                <!--begin::Card body-->
                <div class="card-body pt-0" id="main_table">
                    @include('report::report.admin.performance.product.table')
                </div>
                <!--end::Datatable-->
            </div>
        </div>
    </div>
    <!--end::Products-->
@endsection



@push('scripts')
    <script src="{{ asset('dashboard') }}/assets/js/product/list.js?v=1"></script>

    <script>
        var routeAll = "{{ route('dashboard.report.performance.product.capaste', Request()->all()) }}";
        var route = "{{ route('dashboard.report.performance.product.capaste') }}";
        var csrfToken = "{{ csrf_token() }}";

        $(document).ready(function () {
            KTMenu.createInstances();
        });
    </script>
@endpush
