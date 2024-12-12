@extends($layout)


@section('title', 'Sub Status')

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
                            {{-- <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                    </svg>
                </span> --}}
                            <!--end::Svg Icon-->
                            {{-- <input type="text" data-kt-product-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Sub Status"> --}}
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->

                    @permission('create_SubStatus')
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-customer-table-select="base">
                                <!--begin::Add customer-->
                                <a href="{{ url('order/subStatus/create') }}" class="btn btn-primary"> Add Substatus</a>
                                <!--end::Add customer-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                    @endpermission
                </div>
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
                                        <th class="w-100px sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="Name">#
                                            ID</th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_product_table"
                                            rowspan="1" colspan="1" style="width: 207.25px;">Status Name</th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_product_table"
                                            rowspan="1" colspan="1" style="width: 207.25px;"
                                            aria-label="SKU: activate to sort column ascending">Main status</th>
                                        <th class="min-w-125px text-end sorting_disabled"
                                            style="text-align: start !important; width: 213.5px;" rowspan="1"
                                            colspan="1" aria-label="Active">Remarks</th>
                                        <th class="text-end min-w-70px sorting_disabled" rowspan="1" colspan="1"
                                            style="width: 161.25px;" aria-label="Actions">Actions</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600">
                                    @forelse($subStatuses as $subStatus)
                                        <tr class="odd">
                                            <td>{{ $subStatus->id }}</td>
                                            <td>{{ $subStatus->name }}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $subStatus->status->name->value }}
                                                </span>
                                            </td>
                                            <td>
                                                @forelse($subStatus->remarks as $remark)
                                                    <span class="badge badge-success">{{ $remark->name }}</span>
                                                @empty
                                                    <span>-</span>
                                                @endforelse
                                            </td>

                                            <td class="  text-end">
                                                @canany(['update_SubStatus', 'delete_SubStatus'])
                                                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                        <span class="svg-icon svg-icon-5 m-0">
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                    fill="currentColor"></path>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                    </a>
                                                    <!--begin::Menu-->
                                                    <div class="product-list-actions menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                        data-kt-menu="true">
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        @permission('update_SubStatus')
                                                            <div class="menu-item px-3">
                                                                <a href="{{ url('order/subStatus/edit/' . $subStatus->id) }}"
                                                                    class="menu-link px-3">Edit</a>
                                                            </div>
                                                        @endpermission

                                                        @permission('delete_SubStatus')
                                                            <div class="menu-item px-3">
                                                                <span class="menu-link px-3 delete-sub-status-btn"
                                                                    data-id="{{ $subStatus->id }}">Delete</span>
                                                            </div>
                                                        @endpermission

                                                    </div>
                                                    <!--end::Menu-->
                                                @else
                                                    -
                                                @endcanany
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">No records yet</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            <div id="kt_product_table_processing" class="dataTables_processing" style="display: none;">
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


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all delete buttons
            const deleteButtons = document.querySelectorAll('.delete-sub-status-btn');

            // Add event listener to each delete button
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Get the sub status ID from the data attribute
                    const subStatusId = button.getAttribute('data-id');

                    // Show confirmation dialog using SweetAlert
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this sub status!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to delete the sub status
                            fetch(`/order/subStatus/${subStatusId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => {
                                    if (response.ok) {
                                        // Remove the row from the table
                                        button.closest('tr').remove();
                                        Swal.fire('Deleted!',
                                            'Sub status has been deleted.',
                                            'success');
                                    } else {
                                        console.error('Failed to delete sub status');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
                        }
                    });
                });
            });
        });
    </script>
@endpush

@section('second-sidebar')
    @include('order::layouts.sidebar')
@endsection
