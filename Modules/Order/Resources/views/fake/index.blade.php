@extends($layout)


@section('title', 'Fake')

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
                                Fake Listing
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
                                    Order
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item text-gray-900">
                                    Fake
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
                        <a href="{{ route('fake.scan') }}"
                           class="btn btn-flex btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                            Scan
                        </a>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center">

                            <button type="button" class="btn btn-primary me-3" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                Filter
                            </button>
                            <!-- Sorting Dropdown -->

                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                                 id="kt-toolbar-filter" data-popper-placement="bottom-end">
                                <div class="px-7 py-5">
                                    <div class="fs-4 text-dark fw-bold">Filter Options</div>
                                </div>
                                <div class="separator border-gray-200"></div>

                                <form action="{{ route('fake.index', Request()->all()) }}" method="get">
                                    <div class="px-7 py-5">
                                        <div class="mb-10">

                                            <div class="col-12">
                                                <div class="mb-3">

                                                    <label for="order_id" class="form-label">Customer Phone </label>
                                                    <input type="number" class="form-control" name="customerPhone"
                                                           value="{{ request('customerPhone') ?? old('customerPhone') }}" id="order_id"
                                                           min="0">
                                                </div>
                                            </div>


                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('fake.index') }}" id="reset_filter"
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
                    @include('order::fake.table')
                </div>
                <!--end::Datatable-->
            </div>
            <!--end::Products-->
        </div>
        @endsection
        @push('scripts')
            <script>
                var routeAll = "{{ route('fake.index', Request()->all()) }}";
                var route = "{{ route('fake.index') }}";
                var csrfToken = "{{ csrf_token() }}";
            </script>
    @endpush

    @section('second-sidebar')
        @include('order::layouts.sidebar')
    @endsection
    @push('scripts')

    @endpush