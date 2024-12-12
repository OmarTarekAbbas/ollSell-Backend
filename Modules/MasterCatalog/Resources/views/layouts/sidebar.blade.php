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
            @if(checkCurrent('product'))
            <a href='#' class="btn btn-sm btn-primary fw-bold">New product</a>
            @elseif(checkCurrent('category'))
            <a href='{{route("category.create")}}' class="btn btn-sm btn-primary fw-bold">New Category</a>
    @endif
</div>
<!--end::Actions-->
</div>--}}
<!--end::Header-->
<!--begin::Body-->
<div class="card-body">
    @permission('view_product')
    <!--begin::Item-->
    <div class="d-flex flex-stack {{ checkCurrent('list-products-supplier') ? '' : checkCurrent('product') }}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('product.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Products</a>
                <span class="text-muted fw-semibold d-block fs-7">Display All Products</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('product.index')}}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>

    <!--begin::Separator-->
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->
    @endpermission
    <div class="d-flex flex-stack {{ checkCurrent('list-products-supplier')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('product.listProductsSupplier')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Supplier Products</a>
                <span class="text-muted fw-semibold d-block fs-7">Pending products</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('product.listProductsSupplier')}}" class=" btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    <!--begin::Separator-->
    @permission('view_warehouse')
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->
    <div class="d-flex flex-stack {{ checkCurrent('warehouse')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('warehouse.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Warehouses</a>
                <span class="text-muted fw-semibold d-block fs-7">Display all warehouses</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('warehouse.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    @endpermission
    <!--end::Item-->
    @permission('view_categories')
    <!--begin::Separator-->
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->
    <!--begin::Item-->
    <div class="d-flex flex-stack  {{ checkCurrent('category')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('category.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Categories</a>
                <span class="text-muted fw-semibold d-block fs-7">Display All Categories</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('category.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    @endpermission
    <!--end::Item-->
    <!--begin::Separator-->
    @permission('view_attribute')
    <!--begin::Separator-->
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->
    <!--begin::Item-->
    <div class="d-flex flex-stack  {{ checkCurrent('attribute')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('attribute.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Attributes</a>
                <span class="text-muted fw-semibold d-block fs-7">Display All Attributes</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('attribute.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    @endpermission
    <!--end::Item-->
    @permission('view_events')
    <!--begin::Separator-->
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->
    <!--begin::Item-->
    <div class="d-flex flex-stack  {{ checkCurrent('event')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('event.index')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Events</a>
                <span class="text-muted fw-semibold d-block fs-7">Display All Events</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('event.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
                <i class="ki-outline ki-arrow-right fs-2"></i>
            </a>
            <!--begin::Actions-->
        </div>
        <!--end::Section-->
    </div>
    @endpermission
    <!--end::Item-->
    @permission('view_categories')
    <!--begin::Separator-->
    <div class="separator separator-dashed my-4"></div>
    <!--end::Separator-->

    <div class="d-flex flex-stack  {{ checkCurrent('suggestedCategories')}}">
        <!--begin::Section-->
        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
            <!--begin:Author-->
            <div class="flex-grow-1 me-2">
                <a href="{{route('suggestedCategories.listCategoriesSupplier')}}" class="text-gray-800 text-hover-primary fs-6 fw-bold">
                    Suggested Categories
                </a>
                <span class="text-muted fw-semibold d-block fs-7">Display all</span>
            </div>
            <!--end:Author-->
            <!--begin::Actions-->
            <a href="{{route('event.index')}}" class="btn btn-sm btn-icon btn-secondary w-30px h-30px">
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
