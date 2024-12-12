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
    {{-- <div class="card-header">
        <!--begin::Actions-->
        <div class="text-end w-100">
            @if(checkCurrent('order'))
            <a href='#' class="btn btn-sm btn-primary fw-bold">New Order</a>
            @elseif(checkCurrent('status'))
            <a href='{{route("category.create")}}' class="btn btn-sm btn-primary fw-bold">New Status</a>
            @endif
        </div>
        <!--end::Actions-->
    </div> --}}
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body">
        <!--begin::Item-->
        <div class="d-flex flex-stack {{ checkCurrent('warehouse')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('supplier.warehouse.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Warehouses</a>
                    <span class="text-muted fw-semibold d-block fs-7">Display all warehouses</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="{{route('supplier.warehouse.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
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
        <div class="d-flex flex-stack {{ checkCurrent('product')}}">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="{{route('supplier.product.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Products</a>
                    <span class="text-muted fw-semibold d-block fs-7">Display all products</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="{{route('supplier.product.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
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
    </div>
    <!--end::Body-->
</div>
<!--end::Environment-->
