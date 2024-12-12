@extends($layout)
@section('title', 'Warehouses')
@section('content')
    <!--begin::Products-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
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
                                      transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="currentColor"/>
                            </svg>
                        </span>
                    <!--end::Svg Icon-->
                    <input type="text" data-kt-users-table-filter="search"
                           class="form-control form-control-solid w-250px ps-15" placeholder="Search Users" onkeyup="search(this)" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-customer-table-select="base">
                    <!--begin::Add customer-->
                    <a href="{{ route('supplier.warehouse.create') }}" class="btn btn-primary"> Add Warehouse</a>
                    <!--end::Add customer-->
                </div>
                <!--end::Toolbar-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="container">
                <div class="card-body pt-0" id="main_table">
                    @include('supplier::warehouses.table')
                </div>
            </div>
        </div>
        <!--end::Products-->
        </div>
    </div>
        @endsection
        @push('scripts')
            <script>
                var routeAll = "{{ route('supplier.warehouse.index',Request()->all()) }}";
                var route = "{{ route('supplier.warehouse.index') }}";
                var csrfToken = "{{ csrf_token() }}";
            </script>
            <script>
                   $(document).ready(function () { 
                        KTMenu.createInstances();
                        handleDeleteRows();
                    });

                function search(search){
                    $('#main_table').html('<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>');
            
                    // update URL 
                    full = "{!! url()->full() !!}";
                    if(full.substring(full.lastIndexOf('/') + 1) == 'dropshipper'){
                        searchVal = "?search=" + search.value;
                    }else{
                        searchVal = "&search=" + search.value;
                    }
                    var fullSearchLink = "{!! url()->full() !!}" + searchVal;
                    $.ajax({
                        url: routeAll,
                        type: 'GET',
                        data: {
                            search : search.value
                        },
                        datatype: 'json',
                        success: function (data) { 
                            window.history.pushState("data","Title",fullSearchLink);
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
@include('supplier::layouts.sidebar')
@endsection
