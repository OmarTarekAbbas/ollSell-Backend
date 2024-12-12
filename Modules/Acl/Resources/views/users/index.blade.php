@extends($layout)
@section('title', 'Users')
@section('content')
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
                                          rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                                    <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="currentColor"/>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-users-table-filter="search"
                                   class="form-control form-control-solid w-250px ps-15" placeholder="Search Users"
                                   onkeyup="search(this)"/>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
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
                        <form method="GET" id="filterDataForm" action="{{ route('order.listOrders') }}"
                              class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                              id="kt-toolbar-filter" data-popper-placement="bottom-end">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-4 text-dark fw-bold">Filter Options</div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Separator-->

                            <!--begin::Content-->
                            <div class="px-7 py-5">
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                                    <!--end::Label-->

                                    <!--begin::Options-->
                                    <div class="d-flex flex-column flex-wrap fw-semibold"
                                         data-kt-users-table-filter="status">
                                        <!--begin::Option-->
                                        <label
                                                class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                            <input class="form-check-input" type="radio" name="status" value="1">
                                            <span class="form-check-label text-gray-600">
                                                Active
                                            </span>
                                        </label>
                                        <!--end::Option-->

                                        <!--begin::Option-->
                                        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                            <input class="form-check-input" type="radio" name="status" value="0">
                                            <span class="form-check-label text-gray-600">
                                                Inactive
                                            </span>
                                        </label>
                                        <!--end::Option-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset"
                                            class="btn btn-light btn-active-light-primary me-2 resetFilterDataForm"
                                            data-kt-menu-dismiss="true" data-kt-users-table-filter="reset">Reset
                                    </button>

                                    <button class="btn btn-primary filterDataForm" data-kt-menu-dismiss="true"
                                            data-kt-users-table-filter="filter">Apply
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Content-->
                        </form>

                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-select="base">
                            @permission('create_users')
                            <!--begin::Add customer-->
                            <a href="{{ route('user.create') }}" class="btn btn-primary"> Add User</a>
                            <!--end::Add customer-->
                            @endpermission
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center"
                             data-kt-customer-table-select="selected">
                            <div class="fw-bold me-5">
                                {{-- <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected</div> --}}
                                @permission('delete_users')
                                <button type="button" class="btn btn-danger ms-1 "
                                        data-kt-customer-table-select="delete_selected">Delete Selected
                                </button>
                                @endpermission
                            </div>
                            <!--end::Group actions-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->

                </div>

                <div class="card-body pt-0"  id="main_table">
                    @include('acl::users.table')
                </div>
            </div>
            <!--end::Products-->
        </div>

        @endsection
        @push('scripts')
            <script>
                var routeAll = "{{ route('user.index', Request()->all()) }}";
                var route = "{{ route('user.index') }}";
                var toggleActiveRoute = "{{ route('user.changeStatus') }}";
                var csrfToken = "{{ csrf_token() }}";
                var deletePermission = {{ permissionShow('delete_users') ? 1 : 0 }};
                var updatePermission = {{ permissionShow('update_users') ? 1 : 0 }};
                var showPermission = {{ permissionShow('view_users') ? 1 : 0 }};
                var passwordPermission = {{ permissionShow('change_password_users') ? 1 : 0 }};
            </script>
            <script src="{{ asset('dashboard') }}/assets/js/users/list.js?v=2"></script>

            <script>
                $(document).ready(function () {
                    KTMenu.createInstances();
                    handleDeleteRows();
                });

                function search(search) {
                    $('#main_table').html(
                        '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                    );

                    // update URL
                    full = "{!! url()->full() !!}";
                    if (full.substring(full.lastIndexOf('/') + 1) == 'dropshipper') {
                        searchVal = "?search=" + search.value;
                    } else {
                        searchVal = "&search=" + search.value;
                    }
                    var fullSearchLink = "{!! url()->full() !!}" + searchVal;


                    $.ajax({
                        url: routeAll,
                        type: 'GET',
                        data: {
                            search: search.value
                        },
                        datatype: 'json',
                        success: function (data) {
                            window.history.pushState("data", "Title", fullSearchLink);
                            $('#main_table').html(data);
                            KTMenu.createInstances();
                            handleDeleteRows();
                            initToggleToolbar();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {

                        }
                    });
                }
            </script>

            <script>
                $(document).ready(function () {
                    KTMenu.createInstances();
                    handleDeleteRows();
                    initToggleToolbar();
                });

                function handleDeleteRows() {
                    // Select all delete buttons
                    const deleteButtons = document.querySelectorAll(
                        '[data-kt-users-table-filter="delete_row"]'
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
                                const customerId = parent.querySelectorAll("td div input")[0].value;

                                // SweetAlert2 pop up --- official ad_record reference: https://sweetalert2.github.io/
                                Swal.fire({
                                    text: "Are you sure you want to delete " +
                                        customerName +
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
                                                        customerName +
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
                                            text: customerName + " was not deleted.",
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


                $(".filterDataForm").on("click", function (e) {

                    e.preventDefault();
                    let val = $("#search-input").val();
                    var routeAll =
                        "{{ request()->fullUrl() == request()->url() ? request()->url() . '?' : request()->fullUrl() . '&' }}" +
                        $("#filterDataForm").serialize();
                    $('#main_table').html(
                        '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                    );
                    console.log(routeAll);
                    $.get({
                        url: routeAll,
                        data: {
                            search: val,
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

                $(".resetFilterDataForm").on("click", function (e) {

                    e.preventDefault();
                    let val = $("#search-input").val();
                    var routeAll =
                        "{{ request()->fullUrl() == request()->url() ? request()->url() . '?' : request()->fullUrl() }}";
                    $('#main_table').html(
                        '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                    );
                    console.log(routeAll);
                    $.get({
                        url: routeAll,
                        data: {
                            search: val,
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


                function initToggleToolbar() {
                    // Toggle selected action toolbar
                    // Select all checkboxes
                    const container = document.querySelector("#kt_users_table");
                    const checkboxes = container.querySelectorAll('[type="checkbox"]');


                    // Select elements
                    const deleteSelected = document.querySelector(
                        '[data-kt-customer-table-select="delete_selected"]'
                    );

                    // Toggle delete selected toolbar
                    checkboxes.forEach((c) => {
                        // Checkbox on click event
                        c.addEventListener("click", function () {
                            setTimeout(function () {
                                toggleToolbars();
                            }, 50);
                        });
                    });
                    if (deleteSelected) {
                        // Deleted selected rows
                        deleteSelected.addEventListener("click", function () {

                            // SweetAlert2 pop up --- official users reference: https://sweetalert2.github.io/
                            Swal.fire({
                                text: "Are you sure you want to delete selected users?",
                                icon: "warning",
                                showCancelButton: true,
                                buttonsStyling: false,
                                showLoaderOnConfirm: true,
                                confirmButtonText: "Yes, delete!",
                                cancelButtonText: "No, cancel",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-danger",
                                    cancelButton: "btn fw-bold btn-active-light-primary",
                                },
                            }).then(function (result) {
                                if (result.value) {
                                    $('input[name="item_check"]:checked').each(function (index) {

                                        $.ajax({
                                            method: "POST",
                                            headers: {
                                                "X-CSRF-TOKEN": $(
                                                    'meta[name="csrf-token"]'
                                                ).attr("content"),
                                            },
                                            url: route + "/" + this.value,
                                            data: {
                                                _token: csrfToken,
                                                _method: "DELETE",
                                                id: this.value,
                                            },
                                        }).done(function (res) {

                                            Swal.fire({
                                                text: "Deleting " + customerName,
                                                icon: "info",
                                                buttonsStyling: false,
                                                showConfirmButton: false,
                                                timer: 1,
                                            })
                                            // Remove header checked box
                                            const headerCheckbox =
                                                container.querySelectorAll(
                                                    '[type="checkbox"]'
                                                )[0];
                                            headerCheckbox.checked = false;
                                        }).fail(function (res) {

                                            Swal.fire({
                                                text: res.responseJSON.message,
                                                icon: "error",
                                                buttonsStyling: false,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: {
                                                    confirmButton: "btn fw-bold btn-primary",
                                                },
                                            });
                                        });

                                        Swal.fire({
                                            text: "You have deleted all selected users!.",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            },
                                        }).then(function () {
                                            // delete row data from server and re-draw datatable
                                            dt.draw();
                                        });

                                    });

                                } else if (result.dismiss === "cancel") {
                                    Swal.fire({
                                        text: "Selected users was not deleted.",
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
                    }
                };
            </script>
    @endpush

    @section('second-sidebar')
        @include('acl::layouts.sidebar')
    @endsection
