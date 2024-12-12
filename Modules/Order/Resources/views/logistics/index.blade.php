@extends($layout)


@section('title', 'logistics')

@push('styles')
    <style>
        .swal2-container .swal2-html-container {
            max-height: 500px !important;
        }

        .pop_button:hover {
            cursor: pointer;
        }

        .modal-content {
            width: 130%;
        }
    </style>
@endpush

@section('content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        {{-- <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <input type="text" data-kt-docs-table-filter="search"
                                class="form-control form-control-solid w-250px ps-15" placeholder="Search Orders" />
                        </div> --}}
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3  menu-dropdown"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <i class="ki-duotone ki-filter fs-2"><span class="path1"></span><span
                                        class="path2"></span></i> Filter
                            </button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-500px w-md-500px " data-kt-menu="true"
                                style="z-index: 107; position: fixed; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-256px, 102.4px, 0px);"
                                data-popper-placement="bottom-end">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-4 text-dark fw-bold">Filter Options</div>
                                </div>
                                <!--end::Header-->

                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->

                                <!--begin::Content-->
                                <div class="px-7 py-5" style="position: relative;top: 20px;">
                                    <!--begin::Input group-->
                                    <div class="mb-3">
                                        <!--begin::Label-->
                                        <label class="form-label fs-5 fw-semibold mb-3">Search:</label>
                                        <!--end::Label-->

                                        <!--begin::Options-->
                                        <div class="d-flex flex-column flex-wrap fw-semibold"
                                            data-kt-docs-table-filter="search ">
                                            <input type="text" class="form-control" name="search"
                                                id="exampleFormControlTextarea1">
                                            <div class="error-message"></div>
                                        </div>
                                    </div>
                                    <!--end::Options-->

                                    <div class="d-flex gap-5">
                                        <!--begin::Input group-->
                                        <div class="mb-3">
                                            <!--begin::Label-->
                                            <label class="form-label fs-5 fw-semibold mb-3">Sub Total:</label>
                                            <!--end::Label-->

                                            <!--begin::Options-->
                                            <div class="d-flex flex-column flex-wrap fw-semibold"
                                                data-kt-docs-table-filter="subTotal">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="exampleFormControlInput1"
                                                                class="form-label">From</label>
                                                            <input type="number" min="0"
                                                                class="form-control numberInput" name="fromSubTotal"
                                                                data-kt-docs-table-filter="fromSubTotal"
                                                                id="exampleFormControlInput1">
                                                            <div class="error-message"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="exampleFormControlTextarea1"
                                                                class="form-label">To</label>
                                                            <input type="number" min="0"
                                                                class="form-control numberInput" name="toSubTotal"
                                                                id="exampleFormControlTextarea1">
                                                            <div class="error-message"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Options-->
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-3">
                                            <!--begin::Label-->
                                            <label class="form-label fs-5 fw-semibold mb-3">Grand Total:</label>
                                            <!--end::Label-->

                                            <!--begin::Options-->
                                            <div class="d-flex flex-column flex-wrap fw-semibold"
                                                data-kt-docs-table-filter="grandTotal">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="exampleFormControlInput1"
                                                                class="form-label">From</label>
                                                            <input type="number" min="0"
                                                                class="form-control numberInput" name="fromGrandTotla"
                                                                id="exampleFormControlInput1">
                                                            <div class="error-message"></div>

                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="exampleFormControlTextarea1"
                                                                class="form-label">To</label>
                                                            <input type="number" min="0"
                                                                class="form-control numberInput" name="toGrandTotla"
                                                                id="exampleFormControlTextarea1">
                                                            <div class="error-message"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Options-->
                                    </div>
                                    <!--end::Input group-->

                                    <div class="d-flex gap-5">
                                        <!--begin::Input group-->
                                        <div class="mb-3">
                                            <!--begin::Label-->
                                            <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                                            <!--end::Label-->

                                            <!--begin::Options-->
                                            <div class="d-flex flex-column flex-wrap fw-semibold"
                                                data-kt-docs-table-filter="status_id ">
                                                <select class="form-select" aria-label="Default select status"
                                                    name="statusId">
                                                    <option selected>Open this select menu</option>
                                                    @foreach ($status as $statu)
                                                        <option value="{{ $statu['id'] }}">{{ $statu['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--end::Options-->
                                        <!--begin::Input group-->
                                        <div class="mb-3">
                                            <!--begin::Label-->
                                            <label class="form-label fs-5 fw-semibold mb-3">Payment method:</label>
                                            <!--end::Label-->

                                            <!--begin::Options-->
                                            <div class="d-flex flex-column flex-wrap fw-semibold"
                                                data-kt-docs-table-filter="paymentMethod">
                                                <select class="form-select" aria-label="Default select payment method"
                                                    name="paymentMethod">
                                                    <option selected>Open this select menu</option>
                                                    @foreach ($payments as $payment)
                                                        <option value="{{ $payment['id'] }}">{{ $payment['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--end::Options-->
                                    </div>

                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-3">
                                        <!--begin::Label-->
                                        <label class="form-label fs-5 fw-semibold mb-3">Order Date:</label>
                                        <!--end::Label-->

                                        <!--begin::Options-->
                                        <div class="d-flex flex-column flex-wrap fw-semibold"
                                            data-kt-docs-table-filter="created_at">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">From</label>
                                                        <input type="date" class="form-control" name="fromDate"
                                                            id="exampleFormControlInput1">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlTextarea1"
                                                            class="form-label">To</label>
                                                        <input type="date" class="form-control" name="toDate"
                                                            id="exampleFormControlTextarea1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <!--begin::Label-->
                                        <label class="form-label fs-5 fw-semibold mb-3">Assigned Date:</label>
                                        <!--end::Label-->

                                        <!--begin::Options-->
                                        <div class="d-flex flex-column flex-wrap fw-semibold"
                                             data-kt-docs-table-filter="assigned_at">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                               class="form-label">From</label>
                                                        <input type="date" class="form-control" name="fromAssignedDate"
                                                               id="exampleFormControlInput1">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlTextarea1"
                                                               class="form-label">To</label>
                                                        <input type="date" class="form-control" name="toAssignedDate"
                                                               id="exampleFormControlTextarea1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Options-->
                                    <!--begin::Input group-->
                                    <div class="mb-3">
                                        <!--begin::Label-->
                                        <label class="form-label fs-5 fw-semibold mb-3">Delivery Date:</label>
                                        <!--end::Label-->

                                        <!--begin::Options-->
                                        <div class="d-flex flex-column flex-wrap fw-semibold"
                                            data-kt-docs-table-filter="deliveryDate">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">From</label>
                                                        <input type="date" class="form-control"
                                                            name="fromDeliveryDate" id="exampleFormControlInput1">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlTextarea1"
                                                            class="form-label">To</label>
                                                        <input type="date" class="form-control" name="toDeliveryDate"
                                                            id="exampleFormControlTextarea1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Options-->
                                    <!--end::Input group-->

                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end mb-4">
                                        <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                                            data-kt-menu-dismiss="true" data-kt-docs-table-filter="reset">Reset</button>

                                        <button type="submit" class="btn btn-primary apply-filter-button"
                                            data-kt-menu-dismiss="true" data-kt-docs-table-filter="filter">Apply</button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1--> <!--end::Filter-->
                            @permission('extract_order')
                                <button type="button" class="btn btn-success mx-2" id="exportButton">
                                    <i class="ki-outline ki-exit-up fs-2"></i>
                                    Export Orders
                                </button>
                            @endpermission

                            <!--begin::Add customer-->
                            {{-- <a type="button" class="btn btn-primary" href="{{ route('order.create') }}"
                        data-bs-toggle="tooltip" data-bs-original-title="Coming Soon" data-kt-initialized="1">
                        <i class="ki-duotone ki-plus fs-2"></i> Add Order
                        </a> --}}
                            <!--end::Add customer-->
                        </div>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_example_1">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-start">Order ID</th>
                                <th class="text-start">Dropshipper</th>
                                <th class="text-start">Status</th>
                                <th class="text-start">Sub Total</th>
                                <th class="text-start">Attempts</th>
                                <th class="text-start">First Attempt</th>
                                <th class="text-start">Last Attempt</th>
                                {{-- <th class="text-start">Shipping Fess</th> --}}
                                <th class="text-start">Actions</th>
                            </tr>
                        </thead>

                    </table>
                    <input type="hidden" id="dataTableFilters" name="dataTableFilters" value="">
                    <input type="hidden" id="dataSearch" name="dataSearch" value="">
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modaldata">

            </div>
        </div>
    </div>



    <!--end::Content-->
@endsection
@section('second-sidebar')
    @include('order::layouts.sidebar')
@endsection

@push('scripts')
    <script>
        var updatePermission = {{ permissionShow('update_order') ? 1 : 0 }};
        let request_params_string = "";
        request_params = @json(Request()->all());
        console.log(request_params);
        for (let key in request_params) {
            if (request_params.hasOwnProperty(key)) {
                if (request_params[key] instanceof Array) {
                    for (let i in request_params[key]) {
                        request_params_string += key + "[]=" + request_params[key][i] + "&";
                    }
                } else {
                    request_params_string += key + "=" + request_params[key] + "&";
                }
            }
        }
        request_params_string = request_params_string.trim().replace(/\&$/, '');
        let route = "{{ route('order.Logistics') }}";
        var shippingRoute = "{{ Route('order.startShipping') }}";
        // Set gloabls vars
        let currency = "{{ currency() }}";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="{{ asset('dashboard2/assets/js/datatables/orders.js') }}"></script>
    <script src="{{ asset('dashboard2/assets/js/order/orderStatus.js') }}"></script>

    <script>
        var orderId = null;
        var currentButton = null
        $(document).ready(function() {
            $(".numberInput").mouseleave(function() {
                var inputValue = $(this).val();
                if (inputValue < 0) {
                    $(this).siblings(".error-message").html(
                        '<span class="badge badge-danger">You must not enter a number with a negative value</span>'
                    );
                    $('.apply-filter-button').prop("disabled", true).prop("value", "Disabled");
                } else {
                    $('.apply-filter-button').prop("disabled", false).prop("value", "Enabled");
                    $(this).siblings(".error-message").html('');

                }
            });
        });
        function myOrderModel(ordre_id = 0) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (ordre_id > 0) {
                $.ajax({
                    url: '{{ url('order') }}' + '/' + ordre_id,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        $("#modaldata").html("");
                        $("#modaldata").html(response);
                        $("#exampleModal").modal("show");
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching job status:', error);
                    }
                });
            }

        }
        $(document).ready(function() {
            var captureDataTableFilters = () => {
                var table = $('#kt_datatable_example_1').DataTable();
                var filters = table.settings().toArray().map(setting => setting.oPreviousSearch)
                    .map(filter => filter.sSearch)
                    .filter(search => search !== '');
                // Capture the search value
                var searchData = $('[name="search"]').val();

                // Update the hidden input with DataTable filters
                $('#dataTableFilters').val(filters.join(','));

                return searchData;
            };

            $('#exportButton').click(function() {
                $(this).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                inProgressStatus();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var tableFilters = $('#dataTableFilters').val();
                // Capture filters and search value
                var searchData = captureDataTableFilters();

                var formData = {
                    'job': 'ExportOrdersJob',
                    'filters': tableFilters,
                    'search': searchData,
                    'admin_id': @json(user()->id)
                };

                $.ajax({
                    url: "{{ route('model.export') }}",
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

            $('#kt_datatable_example_1').on('draw.dt', function() {
                captureDataTableFilters();
            });
        });
        var deletePermission = {{ permissionShow('delete_order') ? 1 : 0 }};
        var updatePermission = {{ permissionShow('update_order') ? 1 : 0 }};
        var updateStatusPermission = {{ permissionShow('update_SubStatus') ? 1 : 0 }}
    </script>
@endpush
