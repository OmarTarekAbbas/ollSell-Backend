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
    <!--begin::Header-->
    {{--@if(checkCurrent('order') || checkCurrent('status'))
    <div class="card-header">
        <!--begin::Actions-->
        <div class="text-end w-100">
            @if(checkCurrent('order'))
            <a href='#' class="btn btn-sm btn-primary fw-bold">New Order</a>
            @elseif(checkCurrent('status'))
            <a href='{{route("category.create")}}' class="btn btn-sm btn-primary fw-bold">New Status</a>
            @endif
        </div>
        <!--end::Actions-->
    </div>
    @endif--}}
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body">
        <!--begin::Item-->
        {{-- <div class="d-flex flex-stack {{ checkCurrent('plan')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('plan.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Plans</a>
                    <span class="text-muted fw-semibold d-block fs-7">Control plans</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="{{route('plan.index')}}"" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                    <i class="ki-outline ki-arrow-right fs-2"></i>
                </a>
                <!--begin::Actions-->
            </div>
            <!--end::Section-->
        </div>

        <div class="separator separator-dashed my-4"></div>--}}


        {{-- <div class="d-flex flex-stack {{ checkCurrent('feature')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('feature.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Features</a>
                    <span class="text-muted fw-semibold d-block fs-7">Control Features</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="{{route('plan.index')}}"" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
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
        <div class="d-flex flex-stack {{ checkCurrent('subscriptions')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('plan.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Subscriptions</a>
                    <span class="text-muted fw-semibold d-block fs-7">Dropshipper Subscription</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="{{route('plan.index')}}"" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                    <i class="ki-outline ki-arrow-right fs-2"></i>
                </a>
                <!--begin::Actions-->
            </div>
            <!--end::Section-->
        </div> --}}
        <!--end::Item-->
        @permission('view_RedeemRequest')
        <!--begin::Separator-->
        <div class="separator separator-dashed my-4"></div>
        <!--end::Separator-->

        <!--begin::Item-->
        <div class="d-flex flex-stack {{ checkCurrent('depositRequest')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('depositRequest.list')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Wallet Recharge</a>
                    <span class="text-muted fw-semibold d-block fs-7">Wallet recharge requests</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="{{route('depositRequest.list')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
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

    </div>
    <!--end::Body-->
</div>
<!--end::Environment-->
