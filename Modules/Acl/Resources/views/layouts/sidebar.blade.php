@push('styles')
    <style>
        .card-body .flex-stack {
            padding: 10px;
            border-radius: 5px;
        }

        .card-body .active {
            background-color: #489ef7;
        }

        .card-body .active a {
            color: white !important;
        }

        .card-body .active span {
            color: #fdfdfd !important;
        }
    </style>
@endpush

<!--begin::Environment-->
<div class="card card-reset card-p-0">
    <!--begin::Body-->
    <div class="card-body">
        @permission('view_users')
        <!--begin::Item-->
        <div class="d-flex flex-stack {{ checkCurrent('user') }}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{ route('user.index') }}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Users</a>
                    <span class="text-muted fw-semibold d-block fs-7">Display all users</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="{{ route('order.Logistics') }}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                    <i class="ki-outline ki-arrow-right fs-2"></i>
                </a>
                <!--begin::Actions-->
            </div>
            <!--end::Section-->
        </div>
        <!--end:Author-->
        <!--begin::Actions-->

        <!--begin::Actions-->

        <!--end::Item-->
        @endpermission
        <!--begin::Separator-->
        @permission('view_dropshipper')
        <div class="separator separator-dashed my-4"></div>
        <!--end::Separator-->
        <!--begin::Item-->
        <div class="d-flex flex-stack  {{ checkCurrent('dropshipper')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('dropshipper.index')}}"
                       class="text-gray-800 text-hover-primary fs-6 fw-bold">Dropshippers</a>
                    <span class="text-muted fw-semibold d-block fs-7">Display all dropshipper</span>
                </div>
                <a href="{{ route('dropshipper.index') }}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                    <i class="ki-outline ki-arrow-right fs-2"></i>
                </a>
            </div>
        </div>
        @endpermission
        <!--end::Item-->

        @permission('view_roles')
        <!--begin::Separator-->
        <div class="separator separator-dashed my-4"></div>
        <!--end::Separator-->
        <!--begin::Item-->
        <div class="d-flex flex-stack  {{ checkCurrent('role') }}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{ route('role.index') }}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Roles</a>
                    <span class="text-muted fw-semibold d-block fs-7">All roles</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="{{ route('role.index') }}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                    <i class="ki-outline ki-arrow-right fs-2"></i>
                </a>
                <!--begin::Actions-->
            </div>
            <!--end::Section-->
        </div>
        @endpermission
        <!--end::Item-->

        <!--begin::Separator-->
        @permission('view_suppliers')
        <div class="separator separator-dashed my-4"></div>
        <!--end::Separator-->
        <!--begin::Item-->
        <div class="d-flex flex-stack {{ checkCurrent('suppliers') }}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{ route('suppliers.index') }}"
                       class="text-gray-800 text-hover-primary fs-6 fw-bold">Suppliers</a>
                    <span class="text-muted fw-semibold d-block fs-7">Display all suppliers</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="{{ route('suppliers.index') }}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                    <i class="ki-outline ki-arrow-right fs-2"></i>
                </a>
                <!--begin::Actions-->
            </div>
            <!--end::Section-->
        </div>

        <!--end:Author-->
        <!--begin::Actions-->

        <!--begin::Actions-->


        @endpermission

        <!--end::Item-->
        <!--begin::Separator-->
        <div class="separator separator-dashed my-4"></div>
        <!--end::Separator-->
    </div>
    <!--end::Body-->
</div>
<!--end::Environment-->
