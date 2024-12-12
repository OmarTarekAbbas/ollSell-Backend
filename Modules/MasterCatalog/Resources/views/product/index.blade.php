@extends($layout)

@section('title', 'master catalog')

@section('content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            @if (isset(request()->success) && request()->success == 'true')
                @if ($import_file)
                    <div class="alert alert-success d-flex align-items-center p-5">
                        <!--begin::Icon-->
                        <span class="svg-icon svg-icon-2hx svg-icon-success me-3">
                            <i class="fa-solid fa-check fa-2x"></i>
                        </span>
                        <!--end::Icon-->
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column">
                            <!--begin::Title-->
                            <h4 class="mb-1 text-dark">Success</h4>
                            <!--end::Title-->
                            <!--begin::Content-->
                            <p> {{ __('The File is Uploaded Successfully.') }}</p>
                            @if ($import_file)
                                <ul>
                                    <li>Failed : {{ json_decode($import_file)->failed }}
                                        @if (json_decode($import_file)->failed > 0)
                                            <a download href="{{ route('product.files.download') }}"
                                               class="btn btn-danger btn-sm">Download</a>
                                        @endif
                                    </li>

                                </ul>
                            @endif
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                        <!--begin::Close-->
                        <button type="button"
                                class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                                data-bs-dismiss="alert">
                            <span class="svg-icon svg-icon-2x svg-icon-light">
                                <i class="fa-solid fa-xmark fa-2x"></i>
                            </span>
                        </button>
                        <!--end::Close-->
                    </div>
                @endif
            @endif
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
                                Products Listing
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
                                    Mastercatalog
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <li class="breadcrumb-item text-gray-900">
                                    Products Listing
                                </li>
                                <!--end::Item-->

                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center gap-2 gap-lg-3">

                        @if(request('is_wms') == 1)
                        <a href="{{ route('scan.product.wms') }}"
                            class="btn btn-flex btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                            Scan Product Wms
                        </a>
                        @endif

                            <a href="{{ route('product.importfile') }}"
                               class="btn btn-flex btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                                <i class="ki-outline ki-exit-down fs-2"></i>
                                Import products
                            </a>
                            @permission('extract_product')
                            <button id="exportButton"
                                    class="btn btn-flex btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                                <i class="ki-outline ki-exit-up fs-2"></i>
                                Export products
                            </button>
                            @endpermission
                            @permission('create_product')
                            <a href="{{ route('product.create') }}"
                               class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                Add Product
                            </a>
                            @endpermission
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
                            <input id="search" type="text" data-kt-product-table-filter="search"
                                   class="form-control form-control-solid w-250px ps-15" name="search"
                                   placeholder="Search product" value="{{ request()->search }}" onkeyup="search()"/>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end align-items-center"
                             data-kt-customer-table-select="selected">
                            <div class="fw-bold me-5">
                                <a href="{{ route('product.getDownload') }}" class="btn btn-warning"> Download Example
                                    Excel</a>
                            </div>
                            <!--end::Download Example Excel-->
                        </div>

                        <!--start::Download Example Excel-->
                        <div class="d-flex justify-content-end align-items-center"
                             data-kt-customer-table-select="selected">
                            <div class="fw-bold me-5 d-none">
                                <a href="{{ route('product.getDownload') }}" class="btn btn-warning"> Download Example
                                    Excel</a>
                            </div>
                            <!--end::Download Example Excel-->
                            <div class="fw-bold me-5 d-none">
                                <a href="{{ route('product.importfile') }}" class="btn btn-warning"> Import Excel</a>
                            </div>
                            <!--end::Download Example Excel-->
                        </div>
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 d-flex">
                            <div class="w-100 mw-150px">
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
                                    <form action="{{ route('product.index', Request()->all()) }}" method="get">
                                        <!--begin::Content-->
                                        <div class="px-7 py-5">
                                            <!--begin::Input group-->
                                            <div class="mb-10">

                                                <!--begin::Label-->
                                                <label class="form-label fs-5 fw-semibold mb-3">Joining Date:</label>
                                                <!--end::Label-->
                                                <input type="text" class="form-control"
                                                       name="is_wms" hidden id="is_wms"
                                                       value="{{ request('is_wms') ?? old('is_wms')}}"
                                                       >
                                                <!--begin::Options-->
                                                <div class="d-flex flex-column flex-wrap fw-semibold"
                                                     data-kt-product-table-filter="joining_date">
                                                    <div class="d-flex flex-column flex-wrap fw-semibold"
                                                         data-kt-docs-table-filter="created_at">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <label for="exampleFormControlInput1"
                                                                           class="form-label">From</label>
                                                                    <input type="date" class="form-control"
                                                                           name="fromDate"
                                                                           value="{{ request('fromDate') ?? old('fromDate')}}"
                                                                           id="fromDate">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="mb-3">
                                                                    <label for="exampleFormControlTextarea1"
                                                                           class="form-label">To</label>
                                                                    <input type="date" class="form-control"
                                                                           name="toDate"
                                                                           value="{{ request('toDate') ?? old('toDate')}}"
                                                                           id="toDate">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Options-->

                                                <!--begin::Label-->
                                                <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                                                <!--end::Label-->

                                                <!--begin::Options-->
                                                <div class="d-flex flex-column flex-wrap fw-semibold"
                                                     data-kt-dropshipper-table-filter="status">
                                                    <!--begin::Option-->
                                                    <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                        <input class="form-check-input" type="radio" name="status"
                                                               value=""
                                                                {{ request()->status !== 0 && request() !== 1 ? 'checked' : '' }}>
                                                        <span class="form-check-label text-gray-600">
                                                All
                                            </span>
                                                    </label>
                                                    <!--end::Option-->

                                                    <!--begin::Option-->
                                                    <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                        <input class="form-check-input" type="radio" name="status"
                                                               value="1"
                                                                {{ request()->status == 1 ? 'checked' : '' }}>
                                                        <span class="form-check-label text-gray-600">
                                                Active
                                            </span>
                                                    </label>
                                                    <!--end::Option-->

                                                    <!--begin::Option-->
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="radio" name="status"
                                                               value="0"
                                                                {{ request()->has('status') && request()->status == '0' ? 'checked' : '' }}>
                                                        <span class="form-check-label text-gray-600">
                                                Inactive
                                            </span>
                                                    </label>
                                                    <!--end::Option-->
                                                </div>

                                                <label class="form-label fs-5 fw-semibold mb-3">quantity:</label>
                                                <!--end::Label-->

                                                <!--begin::Options-->
                                                <div class="d-flex flex-column flex-wrap fw-semibold"
                                                     data-kt-dropshipper-table-filter="quantity_status">
                                                    <!--begin::Option-->
                                                    <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                        <input class="form-check-input" type="radio"
                                                               name="quantity_status" value=""
                                                                {{ request()->quantity_status !== 0 && request() !== 1 ? 'checked' : '' }}>
                                                        <span class="form-check-label text-gray-600">
                                                All
                                            </span>
                                                    </label>
                                                    <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                        <input class="form-check-input" type="radio"
                                                               name="quantity_status" value="1"
                                                                {{ request()->quantity_status == 1 ? 'checked' : '' }}>
                                                        <span class="form-check-label text-gray-600">
                                                have stock
                                            </span>
                                                    </label>
                                                    <!--end::Option-->

                                                    <!--begin::Option-->
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="radio"
                                                               name="quantity_status" value="0"
                                                                {{ request()->has('quantity_status') && request()->quantity_status == '0' ? 'checked' : '' }}>
                                                        <span class="form-check-label text-gray-600">
                                                out stock
                                            </span>
                                                    </label>
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="radio"
                                                               name="quantity_status" value="2"
                                                                {{ request()->has('quantity_status') && request()->quantity_status == '2' ? 'checked' : '' }}>
                                                        <span class="form-check-label text-gray-600">
                                                Zero stock
                                            </span>
                                                    </label>
                                                    <!--end::Options-->
                                                </div>
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('product.index') }}"
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
                    @include('mastercatalog::product.table')
                </div>
                <!--end::Datatable-->
            </div>
        </div>
    </div>
    <!--end::Products-->
@endsection

@section('second-sidebar')
    @include('mastercatalog::layouts.sidebar')
@endsection

@push('scripts')
    <script src="{{ asset('dashboard') }}/assets/js/product/list.js?v=1"></script>

    <script>
        var routeAll = "{{ route('product.index', Request()->all()) }}";
        var route = "{{ route('product.index') }}";
        var toggleActiveRoute = "{{ route('product.changeStatus') }}";
        var csrfToken = "{{ csrf_token() }}";
        var deletePermission = {{ permissionShow('delete_product') ? 1 : 0 }};
        var updatePermission = {{ permissionShow('update_product') ? 1 : 0 }};

        function search() {
            // update URL

            full = routeAll;
            if (full.substring(full.lastIndexOf('/') + 1) == 'product') {
                searchVal = "?search=" + $('#search').val() + "&status=" + $('#status').val();
            } else {
                searchVal = "&search=" + $('#search').val() + "&status=" + $('#status').val();
            }
            var fullSearchLink = routeAll + searchVal;

            $('#main_table').html(
                '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
            );

            $.ajax({
                url: route,
                type: 'GET',
                data: {
                    search: $('#search').val(),
                    status: $('#status').val(),
                    quantity_status: $('#quantity_status').val(),
                    fromDate: $('#fromDate').val(),
                    toDate: $('#toDate').val(),
                    is_wms: @json(request('is_wms')),

                },
                datatype: 'json',
                success: function (data) {
                    $('#main_table').html(data);
                    window.history.pushState("data", "Title", fullSearchLink);
                    KTMenu.createInstances();
                    handleDeleteRows();
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        }

        $(".resetFilterDataForm").on("click", function (e) {

            e.preventDefault();
            let val = $("#search-input").val();
            var routeAll = route;
            $('#main_table').html(
                '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
            );
            console.log(routeAll);
            $.get({
                url: routeAll,
                data: {
                    search: val,
                    is_wms: @json(request('is_wms')),
                },
                success: function (data) {
                    jQuery(document).ready(function () {
                        $('#main_table').html(data);
                        KTMenu.createInstances();
                        handleDeleteRows();
                    });
                },
            });
        });
        $('#status').on('change', function () {
            // update URL
            full = routeAll;
            if (full.substring(full.lastIndexOf('/') + 1) == 'product') {
                searchVal = "?search=" + $('#search').val() + "&status=" + $(this).val();
            } else {
                searchVal = "&search=" + $('#search').val() + "&status=" + $(this).val();
            }
            var fullSearchLink = routeAll + searchVal;

            $('#main_table').html(
                '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
            );
            var formData = {
                'search': $('#search').val(),
                'fromDate': fromDate,
                'toDate': toDate,
                'is_wms': @json(request('is_wms')),
                'status': @json(request('status')),
                'quantity_status': @json(request('quantity_status')),
            };
            $.ajax({
                url: route,
                type: 'GET',
                data: formData,
                datatype: 'json',
                success: function (data) {
                    $('#main_table').html(data);
                    window.history.pushState("data", "Title", fullSearchLink);
                    KTMenu.createInstances();
                    handleDeleteRows();
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        })

        $(document).ready(function () {
            KTMenu.createInstances();
            handleDeleteRows();
        });


        function handleDeleteRows() {
            // Select all delete buttons
            const deleteButtons = document.querySelectorAll(
                '[data-kt-product-table-filter="delete_row"]'
            );
            if (deleteButtons) {
                deleteButtons.forEach((d) => {

                    // Delete button on click
                    d.addEventListener("click", function (e) {
                        e.preventDefault();

                        // Select parent row
                        const parent = e.target.closest("tr");

                        // Get customer name
                        const customerName = parent.querySelectorAll("td")[1].innerText;
                        const customerId = parent.querySelectorAll("td")[0].innerText;
                        // SweetAlert2 pop up --- official ad_record reference: https://sweetalert2.github.io/
                        Swal.fire({
                            text: "Are you sure you want to delete " +
                                customerId +
                                "?",
                            icon: "warning",
                            showCancelButton: true,
                            buttonsStyling: false,
                            confirmButtonText: "Yes, delete!",
                            cancelButtonText: "No, cancel",
                            customClass: {
                                confirmButton: "btn fw-bold btn-danger",
                                cancelButton: "btn fw-bold btn-active-light-primary",
                            },
                        }).then(function (result) {
                            if (result.value) {
                                $.ajax({
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": $(
                                            'meta[name="csrf-token"]'
                                        ).attr("content"),
                                    },
                                    url: route + "/" + customerId,
                                    data: {
                                        _token: csrfToken,
                                        _method: "DELETE",
                                        id: customerId,
                                    },
                                })
                                    .done(function (res) {
                                        // Simulate delete request -- for demo purpose only
                                        Swal.fire({
                                            text: "You have deleted " +
                                                customerId +
                                                "!.",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            },
                                        }).then(function () {
                                            // delete row data from server and re-draw datatable
                                            location.reload();
                                        });
                                    })
                                    .fail(function (res) {
                                        Swal.fire({
                                            text: customerId + " was not deleted.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            },
                                        });
                                    });
                            } else if (result.dismiss === "cancel") {
                                Swal.fire({
                                    text: customerId + " was not deleted.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    },
                                });
                            }
                        });
                    });
                });
            }
        }

        $(document).ready(function () {
            $('#exportButton').click(function () {
                $(this).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                inProgressStatus();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var searchData = $('[name="search"]').val();

                var formData = {
                    'job': 'ExportProductsJob',
                    'search': searchData,
                    'fromDate': @json(request('fromDate') ?? null),
                    'toDate': @json(request('toDate') ?? null),
                    'is_wms': @json(request('is_wms') ?? null),
                    'status': @json(request('status') ?? null),
                    'quantity_status': @json(request('quantity_status') ?? null),
                    'admin_id': @json(user()->id)
                };

                $.ajax({
                    url: "{{ route('model.export') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: formData,
                    success: function (response) {
                        console.log(response)
                        $('#exportButton').html(
                            '<i class="ki-outline ki-exit-up fs-2"></i> Export Products');

                        checkJobStatus();
                    },
                    error: function () {
                        Swal.fire({
                            text: "An error occurred while exporting products.",
                            icon: "danger",
                            showCancelButton: false,
                            buttonsStyling: false,
                            confirmButtonText: "Will try later!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-danger",
                                cancelButton: "btn fw-bold btn-active-light-primary",
                            },
                        }).then(function (result) {
                            // do nothing
                        });
                        $('#exportButton').html(
                            '<i class="ki-outline ki-exit-up fs-2"></i> Export Products');
                    }
                });
            });

        });
    </script>
@endpush
