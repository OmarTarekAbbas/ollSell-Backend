@extends($layout)


@section('title', 'Category')

@section('content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Products-->
            <div class="card card-flush">

                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacategory="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" id="search" data-kt-category-table-filter="search"
                                class="form-control form-control-solid w-250px ps-15" placeholder="Search Category"
                                value="{{ request()->search }}" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-customer-table-select="base">
                            @permission('create_categories')
                                <!--begin::Add customer-->
                                <a href="{{ route('category.create') }}" class="btn btn-primary"> Add Category</a>
                                <!--end::Add customer-->
                            @endpermission
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center" data-kt-customer-table-select="selected">
                            <div class="fw-bold me-5">
                                {{-- <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected</div> --}}
                                @permission('delete_categories')
                                    <!-- <button type="button" class="btn btn-danger ms-1 "
                                                    data-kt-customer-table-select="delete_selected">Delete Selected
                                            </button> -->
                                @endpermission
                            </div>
                            <!--end::Group actions-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                </div>

                <div class="card-body pt-0" id="main_table">
                    @include('coredata::category.table')
                </div>
            </div><!--end::Products-->
        </div>


    @endsection

    @section('second-sidebar')
        @include('mastercatalog::layouts.sidebar')
    @endsection

    @push('scripts')
        <script>
            let route = "{{ route('category.index') }}";
            let routeAll = "{{ route('category.index', Request()->all()) }}";
            let csrfToken = "{{ csrf_token() }}";
            let deletePermission = {{ permissionShow('delete_categories') ? 1 : 0 }};
            let updatePermission = {{ permissionShow('update_categories') ? 1 : 0 }};
            let toggleActiveRoute = "{{ route('category.changeStatus') }}";
        </script>
        <script src="{{ asset('dashboard') }}/assets/js/category/list.js?v=12"></script>

        <script>
            $('#search').on('keyup', function() {
                // update URL 
                full = routeAll;
                if (full.substring(full.lastIndexOf('/') + 1) == 'category') {
                    searchVal = "?search=" + $(this).val();
                } else {
                    searchVal = "&search=" + $(this).val();
                }
                var fullSearchLink = routeAll + searchVal;

                $('#main_table').html(
                    '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                );

                $.ajax({
                    url: route,
                    type: 'GET',
                    data: {
                        search: $(this).val(),
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
        </script>
        <script>
            $(document).ready(function() {
                KTMenu.createInstances();
                handleDeleteRows();
            });

            function handleDeleteRows() {
                // Select all delete buttons
                const deleteButtons = document.querySelectorAll(
                    '[data-kt-category-table-filter="delete_row"]'
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
