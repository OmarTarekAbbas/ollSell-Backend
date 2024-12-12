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
    {{--<div class="card-header">
        <!--begin::Actions-->
        <div class="text-end w-100">
            @if(checkCurrent('order'))
            <a href='#' class="btn btn-sm btn-primary fw-bold">New Order</a>
            @elseif(checkCurrent('status'))
            <a href='{{route("category.create")}}' class="btn btn-sm btn-primary fw-bold">New Status</a>
    @endif
</div>
<!--end::Actions-->
</div>--}}
<!--end::Header-->
<!--begin::Body-->
<div class="card-body">
    <!--begin::Item-->
    @permission('view_country')
    <div class="d-flex flex-stack {{ checkCurrent('country')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('country.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Countries</a>
                <span class="text-muted fw-semibold d-block fs-7">Display all Countries</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('country.index')}}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    @endpermission

    <!--end::Item-->
    @permission('view_city')
    <!--begin::Separator-->
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->

    <!--begin::Item-->
    <div class="d-flex flex-stack {{ checkCurrent('city')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('city.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">City</a>
                <span class="text-muted fw-semibold d-block fs-7">Display all Cities</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('city.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    @endpermission
    <!--end::Item-->
    @permission('view_state')
    <!--begin::Separator-->
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->
    <!--begin::Item-->
    <div class="d-flex flex-stack {{ checkCurrent('state')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('state.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">State</a>
                <span class="text-muted fw-semibold d-block fs-7">Display all States</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('state.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    <!--end::Item-->
    @endpermission
    <!--begin::Separator-->
<!--    @permission('view_target_market')
    <div class="separator separator-dashed my-4"></div>
    &lt;!&ndash;end::Separator&ndash;&gt;

    &lt;!&ndash;begin::Item&ndash;&gt;
    <div class="d-flex flex-stack  {{ checkCurrent('target_market')}}">
        &lt;!&ndash;begin::Section&ndash;&gt;
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            &lt;!&ndash;begin:Author&ndash;&gt;
            <div class="flex-grow-1 me-2">
                <a href="{{route('target_market.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Target Markets</a>
                <span class="text-muted fw-semibold d-block fs-7">All Markets</span>
            </div>
            &lt;!&ndash;end:Author&ndash;&gt;
            &lt;!&ndash;begin::Actions&ndash;&gt;
            <a href="{{route('target_market.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            &lt;!&ndash;begin::Actions&ndash;&gt;
        </div>
        &lt;!&ndash;end::Section&ndash;&gt;
    </div>
    &lt;!&ndash;end::Item&ndash;&gt;
    @endpermission-->
    <!--begin::Separator-->
    @permission('view_language')
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->

    <!--begin::Item-->
    <div class="d-flex flex-stack  {{ checkCurrent('language')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('language.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Languages</a>
                <span class="text-muted fw-semibold d-block fs-7">Languages</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('language.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
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
    @permission('view_dropshipper_segmentation')
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->

    <!--begin::Item-->
    <div class="d-flex flex-stack  {{ checkCurrent('dropshipper_segmentation')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('dropshipper_segmentation.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Dropshipper Segmentation</a>
                <span class="text-muted fw-semibold d-block fs-7">Dropshipper Segmentation</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('dropshipper_segmentation.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
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

    @permission('view_onboarding_categories')
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->

    <!--begin::Item-->
    <div class="d-flex flex-stack  {{ checkCurrent('onboarding_categories')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('onboarding_category.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">onboarding categories</a>
                <span class="text-muted fw-semibold d-block fs-7">onboarding categories</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('onboarding_category.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
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

    
</div>
<!--end::Body-->
</div>
<!--end::Environment-->
