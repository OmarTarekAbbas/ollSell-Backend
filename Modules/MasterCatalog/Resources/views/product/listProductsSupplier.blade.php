@extends($layout)

@section('title', 'master catalog')

@section('content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
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
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input id="search" name="search" type="text" data-kt-product-table-filter="search"
                                class="form-control form-control-solid w-250px ps-15" placeholder="Search product"
                                value="{{ request()->search }}" onkeyup="search()" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end align-items-center" data-kt-customer-table-select="selected">

                            <!--end::Download Example Excel-->
                        </div>


                        <!--start::Download Example Excel-->

                        {{-- <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-customer-table-select="base">
                    @permission('create_product')
                    <!--begin::Add customer-->
                    <a href="{{ route('product.create') }}" class="btn btn-primary"> Add Product</a>
                <!--end::Add customer-->
                @endpermission
            </div>

            <!--end::Toolbar-->
            <!--begin::Group actions-->
            <div class="d-flex justify-content-end align-items-center" data-kt-customer-table-select="selected">
                <div class="fw-bold me-5">
                    --}}{{-- <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected</div> --}}{{--
                        @permission('delete_product')
                        <button type="button" class="btn btn-danger ms-1 " data-kt-customer-table-select="delete_selected">Delete Selected
                        </button>
                        @endpermission
                    </div>
                    <!--end::Group actions--> --}}
                    </div>
                    <!--start::Download Example Excel-->

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5 d-flex">
                        <div class="w-100 mw-150px">
                            <!--begin::Select2-->
                            <select id="status" class="form-select form-select-solid" data-control="select2"
                                data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                                <option></option>
                                <option value="1" selected>All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <!--end::Select2-->
                        </div>
                        @permission('create_product')
                            <!--begin::Add product-->
                            <a href="{{ route('product.create') }}" class="btn btn-primary">Add Product</a>
                            <!--end::Add product-->
                        @endpermission
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0" id="main_table">
                    @include('mastercatalog::product.tableListProductsSupplier')
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
        var routeAll = "{{ route('product.listProductsSupplier', Request()->all()) }}";
        var route = "{{ route('product.listProductsSupplier') }}";
        var toggleActiveRoute = "{{ route('product.changeStatus') }}";
        var csrfToken = "{{ csrf_token() }}";

        function search() {
            // update URL
            var full = routeAll;
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

        var deletePermission = {{ permissionShow('delete_product') ? 1 : 0 }};

        var updatePermission = {{ permissionShow('update_product') ? 1 : 0 }};

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
@endpush
