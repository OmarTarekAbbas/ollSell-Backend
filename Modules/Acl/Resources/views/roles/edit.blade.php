<input type="hidden" name="role_id" value="{{ $role->id }}">
<input type="hidden" name="external" value="1" disabled>
<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll" data-kt-scroll="true"
    data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
    data-kt-scroll-dependencies="#kt_modal_add_role_header" data-kt-scroll-wrappers="#kt_modal_add_role_scroll"
    data-kt-scroll-offset="300px">
    <!--begin::Input group-->
    <div class="fv-row mb-10">
        <!--begin::Label-->
        <label class="fs-5 fw-bold form-label mb-2">
            <span class="required">Role name</span>
        </label>
        <!--end::Label-->
        <!--begin::Input-->
        <input class="form-control form-control-solid" placeholder="Enter a role name" name="name"
            value="{{ $role->name }}" />
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
            <input name="type" @if ($role->type) checked="true"
                   @endif value="1" class="external_role form-check-input w-45px h-30px" type="checkbox"
                   id="allowmarketing"
                   onclick="showPopup('external_role',false)"
            >
            <label class="form-check-label" for="allowmarketing"></label>
        </div>
    </div> --}}
    <!--begin::Input group-->
    {{-- <div class="fv-row mb-10">
        <!--begin::Label-->
        <label class="fs-5 fw-bold form-label mb-2">
            <span >Shared Calendar</span>
        </label>
        <!--end::Label-->
        <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
            <input name="share_calender" @if ($role->share_calender) checked="true"
                   @endif value="1" class="shared_calender form-check-input w-45px h-30px" type="checkbox"
                   id="shared_calender"
                   onclick="showPopup('shared_calender')"
            >
            <label class="form-check-label" for="allowmarketing"></label>
        </div>
    </div> --}}
    <!--end::Input group-->
    <!--begin::Permissions-->
    <div class="fv-row" id="all">
        <!--begin::Label-->
        <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
        <!--end::Label-->
        <!--begin::Table wrapper-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <!--begin::Table body-->
                <tbody class="text-gray-600 fw-semibold">
                    <!--begin::Table row-->
                    {{-- <tr>
                    <td class="text-gray-800">Administrator Access
                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                           title="Allows a full access to the system"></i>
                    </td>
                    <td>
                        <!--begin::Checkbox-->
                        <label class="form-check form-check-custom form-check-solid me-9">
                            <input class="form-check-input" type="checkbox" value=""
                                   id="kt_roles_select_all"
                            />
                            <span class="form-check-label" for="kt_roles_select_all">Select all</span>
                        </label>
                        <!--end::Checkbox-->
                    </td>
                </tr> --}}
                    <!--end::Table row-->
                    @foreach ($models as $model)
                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="sticky-cell text-gray-800">{{ ucwords(str_replace('_', ' ', $model)) }}
                            </td>
                            <!--end::Label-->
                            <!--begin::Options-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    @foreach ($permissions as $permission)
                                        @if ($permission->category == $model)
                                            <!--begin::Checkbox-->
                                            <label
                                                class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                <input class="form-check-input" type="checkbox"
                                                    {{ $role_permissions->contains($permission) ? 'checked' : '' }}
                                                    value="on" name="{{ $permission->name }}" />
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
