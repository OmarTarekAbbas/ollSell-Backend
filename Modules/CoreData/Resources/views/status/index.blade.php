@extends($layout)


@section('title', 'Status')

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
                                        rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-product-table-filter="search"
                                class="form-control form-control-solid w-250px ps-15" placeholder="Search Status">
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->

                    @permission('create_status')
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-customer-table-select="base">
                                <!--begin::Add customer-->
                                <a href="{{ url('coredata/status/create') }}" class="btn btn-primary"> Add Status</a>
                                <!--end::Add customer-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                    @endpermission
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <div id="kt_product_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                    id="kt_status_table" aria-describedby="kt_product_table_info" style="width: 1145px;">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="w-10px pe-2 sorting_disabled sorting_asc" rowspan="1"
                                                colspan="1" style="width: 30.5px;"
                                                aria-label="
                        ">
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                        data-kt-check-target="#kt_product_table .form-check-input"
                                                        value="1">
                                                </div>
                                            </th>
                                            <th class="min-w-125px sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 250.5px;" aria-label="Name">Id</th>
                                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_product_table"
                                                rowspan="1" colspan="1" style="width: 207.25px;"
                                                aria-label="SKU: activate to sort column ascending">Name</th>

                                            <th class="min-w-125px text-end sorting_disabled"
                                                style="text-align: start !important; width: 213.5px;" rowspan="1"
                                                colspan="1" aria-label="Active">Active</th>
                                            <th class="text-end min-w-70px sorting_disabled" rowspan="1" colspan="1"
                                                style="width: 161.25px;" aria-label="Actions">Actions</th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-semibold text-gray-600">
                                        @foreach ($status as $value)
                                            <tr class="odd">
                                                <td class="sorting_1">
                                                    <div
                                                        class="form-check form-check-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="checkbox" name="item_check"
                                                            value="37">
                                                    </div>
                                                </td>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ !is_null($value->name) ? $value->name->value : '' }}</td>
                                                <td class="text-end">
                                                    <div
                                                        class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="{{ $value->status }}" name="status" checked=""
                                                            onclick="changeStatus()">
                                                        <label class="form-check-label" id="active-label-1">Active</label>
                                                    </div>
                                                </td>
                                                <td class="  text-end">
                                                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                                        data-kt-menu-trigger="click"
                                                        data-kt-menu-placement="bottom-end">Actions
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                        <span class="svg-icon svg-icon-5 m-0">
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                    fill="currentColor"></path>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon--></a>
                                                    <!--begin::Menu-->
                                                    <div class="product-list-actions menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                        data-kt-menu="true">
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="{{ url('coredata/status/edit/' . $value->id) }}"
                                                                class="menu-link px-3">Edit</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <!-- <div class="menu-item px-3">
                                                <a href="#" data-kt-product-table-filter="delete_row" class="menu-link px-3">Delete</a>
                                            </div> -->
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu-->
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <div id="kt_product_table_processing" class="dataTables_processing"
                                    style="display: none;">
                                    <div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--end::Datatable-->
                </div>
            </div><!--end::Products-->
        </div>
    </div>
    <script>
        var route = "{{ url('coredata/status/changeStatus') }}";

        function changeStatus(id, name, action, className) {

            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
            var table = document.getElementById('kt_status_table');
            var datatable = $('#kt_status_table').DataTable();
            Swal.fire({
                text: "Are you sure you want to " + action + '?',
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes " + action + '!',
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
                            url: route,
                            data: {
                                _token: csrfToken,
                                _method: "POST",
                                id: id,
                                status: action,
                            },
                        })
                        .done(function(res) {
                            // Simulate delete request -- for demo purpose only

                            Swal.fire({
                                text: "You have " + action + ' ' + name + "!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            }).then(function() {

                                if (action == 'active') {
                                    $('.active-btn-' + id).removeClass('d-none');
                                    $('.inactive-btn-' + id).addClass('d-none');
                                } else {
                                    $('.active-btn-' + id).addClass('d-none');
                                    $('.inactive-btn-' + id).removeClass('d-none');
                                }
                            });
                        })
                        .fail(function(res) {
                            Swal.fire({
                                text: name + " was not " + action,
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
                        text: name + " was not " + action,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        },
                    });
                }
            });
        }
    </script>
@endsection

@section('second-sidebar')
    @include('order::layouts.sidebar')
@endsection
