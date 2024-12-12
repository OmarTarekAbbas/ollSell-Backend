@extends($layout)


@section('title', 'Suppliers')

@push('styles')
    <style>
        .swal2-popup {
            width: 600px;
        }

        .swal2-popup .checkOtherSupplier {
            margin-top: 35px;
        }

        .swal2-popup #supplierDropdown {
            margin-top: 30px;
        }
    </style>
@endpush

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
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input type="text" data-kt-users-table-filter="search"
                        class="form-control form-control-solid w-250px ps-15" placeholder="Search Users"
                        onkeyup="search(this)" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-customer-table-select="base">
                    @permission('create_suppliers')
                        <!--begin::Add customer-->
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary"> Add Supplier</a>
                        <!--end::Add customer-->
                    @endpermission
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="container">
            <div class="card-body pt-0" id="main_table">
                @include('acl::suppliers.table')
            </div>
        </div>
    </div>
    </div>
</div>
    <!--end::Products-->
@endsection
@push('scripts')
    <script>
        var routeAll = "{{ route('suppliers.index', Request()->all()) }}";
        var route = "{{ route('suppliers.index') }}";
        var csrfToken = "{{ csrf_token() }}";
    </script>
    <script>
        $(document).ready(function() {
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
                success: function(data) {
                    window.history.pushState("data", "Title", fullSearchLink);
                    $('#main_table').html(data);
                    KTMenu.createInstances();
                    handleDeleteRows();
                    initToggleToolbar();
                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
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

                        // Get customer name and ID
                        const customerName = parent.querySelectorAll("td")[1].innerText;
                        const customerId = parent.querySelectorAll("td div input")[0].value;
                        const currentSupplierId = customerId;
                        var suppliers = @json($suppliersList);

                        // Filter out the current supplier from the list
                        var filteredSuppliers = suppliers.filter(function(supplier) {
                            return supplier.id != currentSupplierId;
                        });
                        // Create the dropdown options
                        const selectOptions = filteredSuppliers.map((supplier) => {
                            return `<option value="${supplier.id}">${supplier.name}</option>`;
                        });
                        // Create a custom Swal (SweetAlert2) modal
                        const swalWithCheckbox = Swal.mixin({
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                                cancelButton: "btn fw-bold btn-active-light-primary",
                            },
                            showCancelButton: true,
                            buttonsStyling: false,
                            showCloseButton: true,
                        });

                        swalWithCheckbox
                            .fire({
                                icon: "warning",
                                title: "CAUTION",
                                html: "You are going to remove this supplier from the system, " +
                                    "this action will cause removing all related products and warehouses from the system also" +
                                    '<br>' +
                                    '<div class="d-flex checkOtherSupplier justify-content-between">' +
                                    '<label for="assignToAnotherSupplier">Assign products and warehouses to another supplier</label>' +
                                    '<input type="checkbox" id="assignToAnotherSupplier">' +
                                    '</div>' +
                                    '<div id="supplierDropdown" style="display: none;">' +
                                    '   <select class="form-control" id="newSupplierId">' +
                                    '       <option value="">Choose a supplier</option>' +
                                    '       ' + selectOptions.join('') +
                                    '   </select>' +
                                    '</div>',
                                showCancelButton: true,
                            })
                            .then(function(result) {
                                if (result.value) {
                                    const assignToAnotherSupplier = document.getElementById(
                                        "assignToAnotherSupplier"
                                    ).checked;
                                    const newSupplierId = document.getElementById("newSupplierId")
                                        .value;

                                    if (assignToAnotherSupplier) {
                                        if (!newSupplierId) {
                                            // Display an error message if no supplier is selected
                                            Swal.fire({
                                                text: "You need to select a supplier to proceed.",
                                                icon: "error",
                                                buttonsStyling: false,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: {
                                                    confirmButton: "btn fw-bold btn-primary",
                                                },
                                            });
                                            return; // Prevent further execution
                                        }

                                        // If a supplier is selected, proceed with the deletion
                                        const requestData = {
                                            _token: csrfToken,
                                            _method: "DELETE",
                                            id: customerId,
                                        };

                                        requestData.newSupplierId = newSupplierId;

                                        // Make the AJAX request and handle success/failure as needed
                                        $.ajax({
                                                method: "POST",
                                                headers: {
                                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]')
                                                        .attr("content"),
                                                },
                                                url: route + "/" + customerId,
                                                data: requestData,
                                            })
                                            .done(function(res) {
                                                // Handle success
                                                Swal.fire({
                                                    text: "You have deleted the supplier!.",
                                                    icon: "success",
                                                    buttonsStyling: false,
                                                    confirmButtonText: "Ok, got it!",
                                                    customClass: {
                                                        confirmButton: "btn fw-bold btn-primary",
                                                    },
                                                }).then(() => {
                                                    location.reload();
                                                })
                                            })
                                            .fail(function(res) {
                                                // Handle failure
                                            });
                                    } else {
                                        // Proceed with deletion without selecting another supplier
                                        const requestData = {
                                            _token: csrfToken,
                                            _method: "DELETE",
                                            id: customerId,
                                        };

                                        // Make the AJAX request and handle success/failure as needed
                                        $.ajax({
                                                method: "POST",
                                                headers: {
                                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]')
                                                        .attr("content"),
                                                },
                                                url: route + "/" + customerId,
                                                data: requestData,
                                            })
                                            .done(function(res) {
                                                Swal.fire({
                                                    text: "You have deleted the supplier!.",
                                                    icon: "success",
                                                    buttonsStyling: false,
                                                    confirmButtonText: "Ok, got it!",
                                                    customClass: {
                                                        confirmButton: "btn fw-bold btn-primary",
                                                    },
                                                }).then(() => {
                                                    location.reload();
                                                })
                                            })
                                            .fail(function(res) {
                                                // Handle failure
                                            });
                                    }
                                }
                            });

                        // Show/hide the supplier dropdown based on the checkbox
                        document.getElementById("assignToAnotherSupplier").addEventListener("change",
                            function() {
                                const supplierDropdown = document.getElementById("supplierDropdown");
                                supplierDropdown.style.display = this.checked ? "block" : "none";
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
                c.addEventListener("click", function() {
                    setTimeout(function() {
                        toggleToolbars();
                    }, 50);
                });
            });
            if (deleteSelected) {
                // Deleted selected rows
                deleteSelected.addEventListener("click", function() {

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
                    }).then(function(result) {
                        if (result.value) {
                            $('input[name="item_check"]:checked').each(function(index) {

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
                                }).done(function(res) {

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
                                }).fail(function(res) {

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
                                }).then(function() {
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
