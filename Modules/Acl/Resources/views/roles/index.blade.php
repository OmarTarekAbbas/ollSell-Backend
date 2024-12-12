@extends($layout)


@section('title', 'Users')

@push('styles')
    <style>
        .table-responsive {
            max-height: 250px;
            overflow-y: auto;
        }

        .table td.sticky-cell {
            position: sticky;
            left: 0;
            background-color: #fff;
            /* Change this to your desired sticky cell background */
            z-index: 1;
        }
    </style>
@endpush

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Products-->
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
                @foreach ($roles as $role)
                    <!--begin::Col-->
                    <div class="col-md-4">
                        <!--begin::Card-->
                        <div class="card card-flush h-md-100">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title flex" style="width: 100%;justify-content: space-between;">
                                    <h2>{{ $role->name }}</h2>
                                    @can('update_roles')
                                        <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" {{ $role->active ? 'checked' : '' }}
                                                onclick="toggleActive({{ $role->id }})">
                                            <label class="form-check-label"
                                                id="active-label-{{ $role->id }}">{{ $role->active ? 'Active' : 'Inactive' }}</label>
                                        </div>
                                    @endcan
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-1">
                                <!--begin::Users-->
                                <div class="fw-bold text-gray-600 mb-5">Total users with this
                                    role: {{ $role->users()->count() }}
                                </div>
                                <!--end::Users-->
                                <!--begin::Permissions-->
                                <div class="d-flex flex-column text-gray-600">
                                    @foreach ($role->permissions as $index => $permission)
                                        @if ($index == 5)
                                        @break
                                    @endif
                                    <div class="d-flex align-items-center py-2">
                                        <span class="bullet bg-primary me-3"></span>{{ $permission->label }}
                                    </div>
                                @endforeach
                                @if ($role->permissions()->count() - 5 > 1)
                                    <div class='d-flex align-items-center py-2'>
                                        <span class='bullet bg-primary me-3'></span>
                                        <em>and {{ $role->permissions()->count() - 5 }} more...</em>
                                    </div>
                                @endif
                            </div>
                            <!--end::Permissions-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer flex-wrap pt-0">
                            @can('show_roles')
                                <a href="{{ route('role.show', $role->id) }}"
                                    class="btn btn-light btn-active-primary my-1 me-2">View Role</a>
                            @endcan
                            @can('update_roles')
                                <button type="button" class="btn btn-light me-1" data-bs-toggle="modal"
                                    onclick="loadRole({{ $role->id }})" data-bs-target="#kt_modal_update_role">Edit Role
                                </button>
                            @endcan
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
            @endforeach
            @can('create_roles')
                <!--begin::Add new card-->
                <div class="ol-md-4">
                    <!--begin::Card-->
                    <div class="card h-md-100">
                        <!--begin::Card body-->
                        <div class="card-body d-flex flex-center">
                            <!--begin::Button-->
                            <button type="button" class="btn btn-clear d-flex flex-column flex-center"
                                data-bs-toggle="modal" data-bs-target="#kt_modal_add_role">
                                <!--begin::Illustration-->
                                <img src="{{ asset('dashboard/') }}/assets/media/illustrations/sigma-1/4.png"
                                    alt="" class="mw-100 mh-150px mb-7" />
                                <!--end::Illustration-->
                                <!--begin::Label-->
                                <div class="fw-bold fs-3 text-gray-600 text-hover-primary">Add New Role</div>
                                <!--end::Label-->
                            </button>
                            <!--begin::Button-->
                        </div>
                        <!--begin::Card body-->
                    </div>
                    <!--begin::Card-->
                </div>
                <!--begin::Add new card-->
            @endcan
        </div>
        <!--end::Products-->
        @can('create_roles')
            <!--begin::Modal - Add role-->
            <div class="modal fade" id="kt_modal_add_role" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-800px">
                    <!--begin::Modal content-->
                    <div class="modal-content" style="width:900px">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Add a Role</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                            transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                            transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-lg-5 mb-7">
                            <!--begin::Form-->
                            <form id="kt_modal_add_role_form" class="form" action="#">
                                @csrf
                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll"
                                    data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                    data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_role_header"
                                    data-kt-scroll-wrappers="#kt_modal_add_role_scroll" data-kt-scroll-offset="300px">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="fs-5 fw-bold form-label mb-2">
                                            <span class="required">Role name</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control form-control-solid" placeholder="Enter a role name"
                                            name="name" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    {{-- <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold form-label mb-2">
                                    <span >External Role</span>
                                </label>
                                <!--end::Label-->
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                    <input name="type" class="form-check-input w-45px h-30px" type="checkbox" value="1">
                                    <label class="form-check-label" for="allowmarketing"></label>
                                </div>
                            </div> --}}
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    {{-- <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold form-label mb-2">
                                    <span >Shared Calender</span>
                                </label>
                                <!--end::Label-->
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                    <input name="share_calender" class="form-check-input w-45px h-30px" type="checkbox"
                                           id="allowmarketing" value="1"
                                    >
                                    <label class="form-check-label" for="allowmarketing"></label>
                                </div>
                            </div> --}}
                                    <!--end::Input group-->
                                    <!--begin::Permissions-->
                                    <div class="fv-row">
                                        <!--begin::Label-->
                                        <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
                                        <!--end::Label-->
                                        <!--begin::Table wrapper-->
                                        <div class="table-responsive" id="all">
                                            <!--begin::Table-->
                                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                                <!--begin::Table body-->
                                                <tbody class="text-gray-600 fw-semibold">
                                                    <!--begin::Table row-->
                                                    <tr>
                                                        <td class="text-gray-800">Administrator Access
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                                data-bs-toggle="tooltip"
                                                                title="Allows a full access to the system"></i>
                                                        </td>
                                                        <td>
                                                            <!--begin::Checkbox-->
                                                            <label
                                                                class="form-check form-check-custom form-check-solid me-9">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="" id="kt_roles_select_all" />
                                                                <span class="form-check-label"
                                                                    for="kt_roles_select_all">Select
                                                                    all</span>
                                                            </label>
                                                            <!--end::Checkbox-->
                                                        </td>
                                                    </tr>
                                                    <!--end::Table row-->
                                                    @foreach ($models as $model)
                                                        <!--begin::Table row-->
                                                        <tr>
                                                            <!--begin::Label-->
                                                            <td class="sticky-cell text-gray-800">
                                                                {{ ucwords(str_replace('_', ' ', $model)) }}
                                                            </td>
                                                            <!--end::Label-->
                                                            <!--begin::Options-->
                                                            <td>
                                                                <!--begin::Wrapper-->
                                                                <div class="d-flex">
                                                                    @foreach ($permissions as $permission)
                                                                        @if (strpos($permission->name, $model) !== false)
                                                                            <!--begin::Checkbox-->
                                                                            <label
                                                                                class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" value="on"
                                                                                    name="{{ $permission->name }}" />
                                                                                <span
                                                                                    class="form-check-label">{{ str_replace(str_replace('_', ' ', $model), '', $permission->label) }}</span>
                                                                            </label>
                                                                            <!--end::Checkbox-->
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                                <!--end::Wrapper-->
                                                            </td>
                                                            <!--end::Options-->
                                                        </tr>
                                                        <!--end::Table row-->
                                                    @endforeach
                                                </tbody>
                                                <!--end::Table body-->
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Table wrapper-->
                                    </div>
                                    <!--end::Permissions-->
                                </div>
                                <!--end::Scroll-->
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="reset" class="btn btn-light me-3"
                                        data-kt-roles-modal-action="cancel">Discard
                                    </button>
                                    <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal - Add role-->
        @endcan
        @can('update_roles')
            <!--begin::Modal - Update role-->
            <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-750px">
                    <!--begin::Modal content-->
                    <div class="modal-content" style="width:900px">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Update Role</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                            rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                            transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5">
                            <!--begin::Form-->
                            <form id="kt_modal_update_role_form" class="form" action="#" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll"
                                    data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                    data-kt-scroll-max-height="auto"
                                    data-kt-scroll-dependencies="#kt_modal_update_role_header"
                                    data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">

                                    <img src="https://static.collectui.com/shots/3678774/dash-loader-large"
                                        alt="">
                                </div>
                                {{-- Ajax here --}}
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="reset" class="btn btn-light me-3"
                                        data-kt-roles-modal-action="cancel">
                                        Discard
                                    </button>
                                    <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal - Update role-->
        @endcan
    </div>
</div>
@endsection

@section('second-sidebar')
@include('acl::layouts.sidebar')
@endsection


@push('scripts')
<script>
    function showPopup(class_name, check_enabled = true) {
        let checked_2;
        let checked = checked_2 = $('.' + class_name).is(":checked");
        let message =
            "This action will remove the private calendars data for the affected users, Are you sure you want to proceed?";
        if (check_enabled == false) {
            // show popup if not enabled
            checked_2 = !checked;
            message =
                "This action will hide the External Role additional data from the affected users, Are you sure you want to proceed?";
        }

        if (class_name == "external_role") {
            if (checked) {
                $(`input[name="external"]`).prop('disabled', false);
            } else {
                $(`input[name="external"]`).prop('disabled', true);
            }
        }

        if (checked_2) {
            Swal.fire({
                text: message,
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light",
                },
            }).then(function(result) {
                if (check_enabled) {
                    if (result.isConfirmed == true) {
                        $('.' + class_name).prop('checked', true); // Checks it
                    } else {
                        $('.' + class_name).prop('checked', false); // Checks it
                    }
                } else {
                    if (result.isConfirmed == true) {
                        $('.' + class_name).prop('checked', false); // Checks it
                        $(`input[name="external"]`).prop('disabled', true);
                    } else {
                        $('.' + class_name).prop('checked', true); // Checks it
                        $(`input[name="external"]`).prop('disabled', false);
                    }
                }
            });
        }
    }
    let route = "{{ route('role.store') }}";
    let csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('dashboard/') }}/assets/js/roles/add.js?v=61721112022"></script>
<script src="{{ asset('dashboard/') }}/assets/js/roles/update.js?v=61721112022"></script>
<script>
    $(document).ready(function() {
        // This WILL work because we are listening on the 'document',
        // for a click on an element with an ID of #test-element
        $(document).on("click", ".swal2-confirm", function() {
            $('#kt_modal_add_role').modal('hide');
        });
    });
    let toggleActive = function(id) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: `{{ route('role.changeStatus') }}`,
            headers: {
                "X-CSRF-TOKEN": $(
                    'meta[name="csrf-token"]'
                ).attr("content"),
            },
            data: {
                _token: csrfToken,
                'id': id
            },
            success: function(data) {
                let labelItem = $('#active-label-' + id)
                let label = data.status == 'true' ? 'Active' : 'Inactive';
                labelItem.html(label);
            }
        });
    }
</script>
@endpush
