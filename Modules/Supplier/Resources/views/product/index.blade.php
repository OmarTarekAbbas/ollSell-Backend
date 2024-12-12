@extends($layout)
@section('title', 'master catalog')

@section('content')
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
                                    <a download href="{{ route('supplier.product.files.download') }}"
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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                        Products Listing
                    </h1>
                    <!--end::Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ url('/supplier/index') }}" class="text-muted text-hover-primary">
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
                    {{--
                        <a href="{{ route('supplier.product.importfile') }}"
                        class="btn btn-flex btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                        <i class="ki-outline ki-exit-down fs-2"></i>
                        Import products
                    </a>
                        --}}
                  
                    <a href="{{ route('supplier.product.importbasicfile') }}"
                    class="btn btn-flex btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                    <i class="ki-outline ki-exit-down fs-2"></i>
                    Import Basic products
                    </a>

                    <button id="exportButton"
                        class="btn btn-flex btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold">
                        <i class="ki-outline ki-exit-up fs-2"></i>
                        Export products
                    </button>

                    <a href="{{ route('supplier.product.create') }}" class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                        Add Product
                    </a>
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
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input id="search" type="text" data-kt-product-table-filter="search"
                        class="form-control form-control-solid w-250px ps-15" name="search" placeholder="Search product"
                        value="{{ request()->search }}" onkeyup="search()"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
            {{--
                <div class="d-flex justify-content-end align-items-center" data-kt-customer-table-select="selected">
                    <div class="fw-bold me-5">
                        <a href="{{ route('supplier.product.getDownload','all') }}" class="btn btn-warning">
                            Download Example Excel
                        </a>
                    </div>
                    <!--end::Download Example Excel-->
                </div>
                --}}
                <div class="d-flex justify-content-end align-items-center" data-kt-customer-table-select="selected">
                    <div class="fw-bold me-5">
                        <a href="{{ route('supplier.product.getDownload','Basic') }}" class="btn btn-warning">
                            Download Example Basic Excel
                        </a>
                    </div>
                    <!--end::Download Example Excel-->
                </div>
            </div>
  
            <!--end::Card header-->
  
        </div>
                  <!--begin::Card body-->
                  <div class="card-body pt-0" id="main_table">
                    @include('supplier::product.table')
                </div>
                <!--end::Datatable-->
        <!--end::Products-->
    </div>
    </div>
</div>
@endsection

@section('second-sidebar')
    @include('supplier::layouts.sidebar')
@endsection

@push('scripts')
    <script>
        var routeAll = "{{ route('supplier.product.index', Request()->all()) }}";
        var route = "{{ route('supplier.product.index') }}";
        var toggleActiveRoute = "{{ route('supplier.product.changeStatus') }}";
        var csrfToken = "{{ csrf_token() }}";
        var deletePermission = {{ permissionShow('delete_product') ? 1 : 0 }};
        var updatePermission = {{ permissionShow('update_product') ? 1 : 0 }};
    </script>
    <script src="{{ asset('dashboard') }}/assets/js/product/list.js?v=1"></script>

    <script>
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
                },
                datatype: 'json',
                success: function(data) {
                    $('#main_table').html(data);
                    window.history.pushState("data", "Title", fullSearchLink);
                    KTMenu.createInstances();
                    handleDeleteRows();
                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });
        }
        $('#status').on('change', function() {
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

            $.ajax({
                url: route,
                type: 'GET',
                data: {
                    search: $('#search').val(),
                    status: $(this).val()
                },
                datatype: 'json',
                success: function(data) {
                    $('#main_table').html(data);
                    window.history.pushState("data", "Title", fullSearchLink);
                    KTMenu.createInstances();
                    handleDeleteRows();
                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });
        })
        $(document).ready(function() {
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
                    d.addEventListener("click", function(e) {
                        e.preventDefault();

                        // Select parent row
                        const parent = e.target.closest("tr");

                        // Get customer name
                        const customerName = parent.querySelectorAll("td")[1].innerText;
                        const customerId = parent.querySelectorAll("td div input")[0].value;

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
                        }).then(function(result) {
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
                                    .done(function(res) {
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
                                        }).then(function() {
                                            // delete row data from server and re-draw datatable
                                            location.reload();
                                        });
                                    })
                                    .fail(function(res) {
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
    </script>

<script>
    $(document).ready(function() {
        $('#exportButton').click(function() {
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            inProgressStatus();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var searchData = $('[name="search"]').val();

            var formData = {
                'job': 'ExportSupplierProductsJob',
                'search': searchData,
            };

            $.ajax({
                url: "{{ route('supplier.model.export') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                success: function(response) {
                    $('#exportButton').html(
                        '<i class="ki-outline ki-exit-up fs-2"></i> Export Orders');

                    checkJobStatus();
                },
                error: function() {
                    Swal.fire({
                        text: "An error occurred while exporting orders.",
                        icon: "danger",
                        showCancelButton: false,
                        buttonsStyling: false,
                        confirmButtonText: "Will try later!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary",
                        },
                    }).then(function(result) {
                        // do nothing
                    });
                    $('#exportButton').html(
                        '<i class="ki-outline ki-exit-up fs-2"></i> Export Orders');
                }
            });
        });

    });
</script>
@endpush
