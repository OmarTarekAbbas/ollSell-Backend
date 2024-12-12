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
        @permission('view_order')
            <!--begin::Item-->
            <div class="d-flex flex-stack {{ checkCurrent('logistics') }}">
                <!--begin::Section-->
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <!--begin:Author-->
                    <div class="flex-grow-1 me-2">
                        <a href="{{ route('order.Logistics') }}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Order
                            Logistics</a>
                        <span class="text-muted fw-semibold d-block fs-7">Order Logistics</span>
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
            <!--end::Item-->

            <div class="separator separator-dashed my-4"></div>


            <!--begin::Item-->
            <div class="d-flex flex-stack {{ checkCurrent('listing') }}">
                <!--begin::Section-->
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <!--begin:Author-->
                    <div class="flex-grow-1 me-2">
                        <a href="{{ route('order.listing.index') }}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Order
                            List</a>
                        <span class="fw-semibold fs-7 d-block text-start text-success ps-0">Enhancement</span>
                    </div>
                    <!--end:Author-->
                    <!--begin::Actions-->
                    <a href="{{ route('order.listing.index') }}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                        <i class="ki-outline ki-arrow-right fs-2"></i>
                    </a>
                    <!--begin::Actions-->
                </div>
                <!--end::Section-->
            </div>
            <!--end::Item-->

            <!--begin::Item-->
            <div class="d-flex flex-stack {{ checkCurrent('cancelled') }}">
                <!--begin::Section-->
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <!--begin:Author-->
                    <div class="flex-grow-1 me-2">
                        <a href="{{ route('order.cancelled.index') }}" class="text-gray-800 text-hover-primary fs-6 fw-bold">
                            Cancelled Validation
                        </a>
                        <span class="fw-semibold fs-7 d-block text-start text-success ps-0">Processing</span>
                    </div>
                    <!--end:Author-->
                    <!--begin::Actions-->
                    <a href="{{ route('order.cancelled.index') }}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                        <i class="ki-outline ki-arrow-right fs-2"></i>
                    </a>
                    <!--begin::Actions-->
                </div>
                <!--end::Section-->
            </div>
            <!--end::Item-->

            <!--begin::Separator-->
            <div class="separator separator-dashed my-4"></div>
            <!--end::Separator-->
            <!--begin::Item-->
            <div class="d-flex flex-stack {{ checkCurrent('followUp') }}">
                <!--begin::Section-->
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <!--begin:Author-->
                    <div class="flex-grow-1 me-2">
                        <a href="{{ route('order.followUp.index') }}"
                            class="text-gray-800 text-hover-primary fs-6 fw-bold">Follow-Up</a>
                        <span class="text-muted fw-semibold d-block fs-7">Follow-Up</span>
                    </div>
                    <!--end:Author-->
                    <!--begin::Actions-->
                    <a href="{{ route('order.followUp.index') }}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                        <i class="ki-outline ki-arrow-right fs-2"></i>
                    </a>
                    <!--begin::Actions-->
                </div>
                <!--end::Section-->
            </div>
            <!--end::Item-->
            <!--begin::Separator-->
            <div class="separator separator-dashed my-4"></div>
            <!--end::Separator-->
         <!--begin::Item-->
            <div class="d-flex flex-stack {{ checkCurrent('refund') }}">
                <!--begin::Section-->
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <!--begin:Author-->
                    <div class="flex-grow-1 me-2">
                        <a href="{{ route('order.refund.index') }}"
                            class="text-gray-800 text-hover-primary fs-6 fw-bold">Order Refunds</a>
                        <span class="text-muted fw-semibold d-block fs-7">All order refund</span>
                    </div>
                    <!--end:Author-->
                    <!--begin::Actions-->
                    <a href="{{ route('order.refund.index') }}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                        <i class="ki-outline ki-arrow-right fs-2"></i>
                    </a>
                    <!--begin::Actions-->
                </div>
                <!--end::Section-->
            </div>
            <!--end::Item-->

            <div class="separator separator-dashed my-4"></div>
        @endpermission
        @permission('view_SubStatus')
            <!--begin::Item-->
            <div class="d-flex flex-stack {{ checkCurrent('subStatus') }}">
                <!--begin::Section-->
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <!--begin:Author-->
                    <div class="flex-grow-1 me-2">
                        <a href="{{ route('order.subStatus.index') }}"
                            class="text-gray-800 text-hover-primary fs-6 fw-bold">Substatus</a>
                        <span class="text-muted fw-semibold d-block fs-7">List all Substatus</span>
                    </div>
                    <!--end:Author-->
                    <!--begin::Actions-->
                    <a href="{{ route('order.subStatus.index') }}"
                        class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                        <i class="ki-outline ki-arrow-right fs-2"></i>
                    </a>
                    <!--begin::Actions-->
                </div>
                <!--end::Section-->
            </div>
            <!--end::Item-->
            <!--begin::Separator-->
            <div class="separator separator-dashed my-4"></div>
            <!--end::Separator-->
        @endpermission
        @permission('view_RedeemRequest')
            <!--begin::Item-->
            <div class="d-flex flex-stack  {{ checkCurrent('withdrawalRequest') }}">
                <!--begin::Section-->
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <!--begin:Author-->
                    <div class="flex-grow-1 me-2">
                        <a href="{{ route('withdrawalRequest.list') }}"
                            class="text-gray-800 text-hover-primary fs-6 fw-bold">Withdrawal Requests</a>
                        <span class="text-muted fw-semibold d-block fs-7">Withdraw requests</span>
                    </div>
                    <!--end:Author-->
                    <!--begin::Actions-->
                    <a href="{{ route('withdrawalRequest.list') }}"
                        class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                        <i class="ki-outline ki-arrow-right fs-2"></i>
                    </a>
                    <!--begin::Actions-->
                </div>
                <!--end::Section-->
            </div>
        @endpermission
        <!--end::Item-->
        <!--begin::Separator-->
        <div class="separator separator-dashed my-4"></div>
        <!--end::Separator-->

        @permission('view_RedeemRequest')
            <!--begin::Item-->
            <div class="d-flex flex-stack {{ checkCurrent('depositRequest') }}">
                <!--begin::Section-->
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <!--begin:Author-->
                    <div class="flex-grow-1 me-2">
                        <a href="{{ route('depositRequest.list') }}"
                            class="text-gray-800 text-hover-primary fs-6 fw-bold">Wallet Recharge</a>
                        <span class="text-muted fw-semibold d-block fs-7">Wallet recharge requests</span>
                    </div>
                    <!--end:Author-->
                    <!--begin::Actions-->
                    <a href="{{ route('depositRequest.list') }}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                        <i class="ki-outline ki-arrow-right fs-2"></i>
                    </a>
                    <!--begin::Actions-->
                </div>
                <!--end::Section-->
            </div>
        @endpermission
    </div>
    <!--end::Body-->
</div>
<!--end::Environment-->
