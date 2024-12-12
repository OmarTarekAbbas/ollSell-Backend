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
        <!--begin::Item-->
        <div class="d-flex flex-stack {{ checkCurrent('default')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('dashboard.report.default', ['period' => 'this_month'])}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Repots</a>
                </div>
                <a href="{{route('dashboard.report.default', ['period' => 'this_month'])}}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                    <i class="ki-outline ki-arrow-right fs-2"></i></a>
                <!--end:Author-->
            </div>
            <!--end::Section-->
        </div>
        <div class="separator separator-dashed my-4"></div>
        @can('product_report')
        <div class="d-flex flex-stack {{ checkCurrent('all')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('dashboard.report.product.all', ['period' => 'this_month'])}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Product</a>
                </div>
                <a href="{{route('dashboard.report.product.all', ['period' => 'this_month'])}}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                    <i class="ki-outline ki-arrow-right fs-2"></i></a>
                <!--end:Author-->
            </div>
            <!--end::Section-->
        </div>
        <!--end::Item-->
        <!--begin::Separator-->
        <div class="separator separator-dashed my-4"></div>
        <!--end::Separator-->
        @endcan

        <div class="d-flex flex-stack {{ checkCurrent('validation')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('dashboard.report.validation.getStats')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Validation</a>
                </div>
            </div>
            <!--end::Section-->
        </div>
        <!--end::Item-->
        <!--begin::Separator-->
        <div class="separator separator-dashed my-4"></div>
        <!--end::Separator-->
    </div>
    <!--end::Body-->
</div>
<!--end::Environment-->
