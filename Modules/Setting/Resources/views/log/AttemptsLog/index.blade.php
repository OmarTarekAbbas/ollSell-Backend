@extends($layout)


@section('title', 'Validation Log')

@section('content')

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
                                Validation Log Listing
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
                                    Log
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item text-gray-900">
                                    Validation Log Listing
                                </li>
                                <!--end::Item-->

                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <!--end::Actions-->
                    </div>
                    <!--end::Toolbar wrapper-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--begin::Products-->
            <div class="card card-flush overflow-auto">
                @include('dashboard.error.error')
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->

                            <!--end::Svg Icon-->

                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->

                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center">
                            <a href="{{ route('log.validation_log.export', array_merge(Request()->all(), ['sort_by' => 'id', 'sort_order' => 'desc'])) }}"
                                class="btn btn-success me-3">
                                Export Validation Log
                            </a>

                            <button type="button" class="btn btn-primary me-3" data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                                Filter
                            </button>
                            <!-- Sorting Dropdown -->
                            <div class="d-flex justify-content-end align-items-center">
                                <form action="{{ route('log.validation_log.index', Request()->all()) }}" method="get">
                                    <div class="input-group me-3">
                                        <select class="form-select" name="sort_by" onchange="this.form.submit()">
                                            <option value="created_at_desc"
                                                {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Sort by
                                                Timestamp (Desc)</option>
                                            <option value="created_at_asc"
                                                {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Sort by
                                                Timestamp (Asc)</option>
                                            <option value="id_asc" {{ request('sort_by') == 'id_asc' ? 'selected' : '' }}>
                                                Sort by Order ID (Asc)</option>
                                            <option value="id_desc" {{ request('sort_by') == 'id_desc' ? 'selected' : '' }}>
                                                Sort by Order ID (Desc)</option>
                                        </select>
                                    </div>
                                </form>
                            </div>


                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                                id="kt-toolbar-filter" data-popper-placement="bottom-end">
                                <div class="px-7 py-5">
                                    <div class="fs-4 text-dark fw-bold">Filter Options</div>
                                </div>
                                <div class="separator border-gray-200"></div>

                                <form action="{{ route('log.validation_log.index', Request()->all()) }}" method="get">
                                    <div class="px-7 py-5">
                                        <div class="mb-10">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="order_id" class="form-label">Order ID</label>
                                                    <input type="number" class="form-control" name="order_id"
                                                        value="{{ request('order_id') ?? old('order_id') }}" id="order_id"
                                                        min="0">
                                                </div>
                                            </div>

                                            <label class="form-label fs-5 fw-semibold mb-3">Created Date:</label>

                                            <div class="d-flex flex-column flex-wrap fw-semibold">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label for="fromDate" class="form-label">From</label>
                                                            <input type="datetime-local" class="form-control"
                                                                name="fromDate"
                                                                value="{{ request('fromDate') ?? old('fromDate') }}"
                                                                id="fromDate">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label for="toDate" class="form-label">To</label>
                                                            <input type="datetime-local" class="form-control" name="toDate"
                                                                value="{{ request('toDate') ?? old('toDate') }}"
                                                                id="toDate">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('log.validation_log.index') }}" id="reset_filter"
                                                class="btn btn-light btn-active-danger fw-semibold me-2 px-6">Reset</a>
                                            <button type="submit" class="btn btn-primary"
                                                data-kt-menu-dismiss="true">Apply</button>
                                        </div>
                                    </div>
                                </form>


                            </div>
                        </div>

                        <!--end::Card toolbar-->
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0" id="main_table">
                    @include('setting::log.AttemptsLog.table')
                </div>
                <!--end::Datatable-->
            </div>
            <!--end::Products-->
        </div>
    @endsection
    @push('scripts')
        <script>
            var routeAll = "{{ route('log.validation_log.index', Request()->all()) }}";
            var route = "{{ route('log.validation_log.index') }}";
            var csrfToken = "{{ csrf_token() }}";
        </script>
    @endpush

    @section('second-sidebar')
        @include('setting::layouts.sidebar')
    @endsection
    @push('scripts')
        <script>
            $(".resetFilterDataForm").on("click", function(e) {
                e.preventDefault();
                var routeAll = route;
                $('#main_table').html(
                    '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                );
                $.get({
                    url: routeAll,
                    success: function(data) {
                        jQuery(document).ready(function() {
                            $('#main_table').html(data);
                            KTMenu.createInstances();
                            handleDeleteRows();
                        });
                    },
                });
            });
        </script>
    @endpush
