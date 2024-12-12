@extends($layout)


@section('title', 'Create Product')
@push('styles')
<style>
.autocomplete ul {
    list-style: none;
    padding: 0;
    display: none;
    border: 1px solid #ccc;
    border-top: none;
    position: absolute;
    z-index: 1;
}

.autocomplete li {
    padding: 5px;
    cursor: pointer;
}

.autocomplete li:hover {
    background-color: #f2f2f2;
}

</style>

@endpush

@section('content')
    <style>
        #select2-warehouse_id-results li:first-child {
            background: #e9e9e9;
        }
    </style>

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-4 py-lg-8">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Product
                            Form</h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::Form-->
                @include('dashboard.error.error')
                <form class="form d-flex flex-column flex-lg-row" action="{{ route('supplier.product.index') }}"
                    enctype="multipart/form-data" method="POST">
                    @csrf
                    <!--begin::Aside column-->
                    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                        <!--begin::Thumbnail settings-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Thumbnail</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body text-center pt-0">
                                <!--begin::Image input-->
                                <!--begin::Image input placeholder-->
                                <style>
                                    .image-input-placeholder {
                                        background-image: url('{{ asset('dashboard') }}/assets/media/svg/files/blank-image.svg');
                                    }

                                    [data-bs-theme="dark"] .image-input-placeholder {
                                        background-image: url('assets/media/svg/files/blank-image-dark.svg');
                                    }
                                </style>
                                <!--end::Image input placeholder-->
                                <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                                    data-kt-image-input="true">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-150px h-150px"></div>
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Label-->
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                        <i class="ki-outline ki-pencil fs-7"></i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="thumbnail" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="thumbnail_remove" />
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Cancel-->
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                    </span>
                                    <!--end::Cancel-->
                                    <!--begin::Remove-->
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                    </span>
                                    <!--end::Remove-->
                                </div>
                                <!--end::Image input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg
                                    image files are accepted</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Thumbnail settings-->

                        <!--begin::Barcode settings-->
                        <!--begin::Status-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Barcode</h2>
                                </div>
                                <!--end::Card title-->

                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body text-center pt-0">
                                <div class="input-group mb-3">
                                    <input type="text" name="barcode" value="{{ old('barcode') }}"
                                        pattern="[A-Za-z0-9-]{5,50}"
                                        oninput="this.value = this.value.replace(/[^A-Za-z0-9-]/g, '').replace(/(\..*)\./g, '$1');"
                                        title="Letters, numbers, and hyphen only, 5-50 characters" class="form-control" />
                                </div>
                                <!-- Additional description and details if needed -->
                                <div class="text-muted fs-7">
                                    Set the product barcode. Letters, numbers, and hyphen only. 5-50 characters.
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Status-->


                        <!--end::Barcode settings-->
                        <!--begin::Status-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title required">
                                    <h2>Status</h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <div class="rounded-circle bg-success w-15px h-15px"
                                        id="kt_ecommerce_add_product_status"></div>
                                </div>
                                <!--begin::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Select2-->
                                <select class="form-select mb-2" name="status" data-control="select2"
                                    data-hide-search="true" data-placeholder="Select an option"
                                    id="kt_ecommerce_add_product_status_select" required>
                                    <option></option>
                                    <option {{ old('status') == '1' ? 'selected' : '' }} value="1">Live</option>
                                    <option {{ old('status') == '0' ? 'selected' : '' }} value="0">Inactive</option>
                                </select>
                                <!--end::Select2-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Set the product status.</div>
                                <!--end::Description-->
                                <!--begin::Datepicker-->
                                <div class="d-none mt-10">
                                    <label for="kt_ecommerce_add_product_status_datepicker" class="form-label">Select
                                        publishing date and time</label>
                                    <input class="form-control" id="kt_ecommerce_add_product_status_datepicker"
                                        placeholder="Pick date & time" />
                                </div>
                                <!--end::Datepicker-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Status-->
                        <!--begin::Category & tags-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Product Details</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <!--begin::Label-->
                                <label class="form-label">Categories</label>
                                <!--end::Label-->
                                <!--begin::Select2-->
                                <select class="form-select mb-2" name="category_Ids[]" multiple data-control="select2"
                                    data-placeholder="Select an option" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($categories as $value)
                                        @php
                                            $isFavorite = in_array($value->id, (array) old('category_Ids', []));
                                        @endphp
                                        <option data-commission-percentage="{{ $value->commission }}"
                                            {{ $isFavorite ? 'selected' : '' }} value="{{ $value->id }}">
                                            {{ $value->name->value }}
                                        </option>
                                    @endforeach
                                </select>
                                <!--end::Select2-->
                                <!--begin::Description-->
                                {{-- <div class="text-muted fs-7 mb-7">Add product to a category.</div> --}}
                                <!--end::Description-->
                                <!--end::Input group-->
                                <!--begin::Button-->
                                <div id="suggestCategory" class="btn btn-light-primary btn-sm mb-10">
                                    <i class="ki-outline ki-plus fs-2"></i>Suggest a Category
                                </div>
                                <!--end::Button-->
                                <!--end::Input group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Category & tags-->
                    </div>
                    <!--end::Aside column-->
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                    href="#kt_ecommerce_add_product_general">Basic</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                    href="#kt_ecommerce_add_product_advanced">Advanced</a>
                            </li>
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                    href="#kt_ecommerce_add_product_variations">Variations</a>
                            </li>
                            <!--end:::Tab item-->
                        </ul>
                        <!--end:::Tabs-->
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general"
                                role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <!--begin::General options-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Basic</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body row pt-0">
                                                <div class="col">

                                                    <!--begin::Input group-->
                                                    <div class="mb-10 fv-row">
                                                        <!--begin::Label-->
                                                        <label class="required form-label">Product Name

                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="text" name="name"
                                                            class="form-control mb-2" placeholder="Product name"
                                                            value="{{ old('name') ? old('name') : '' }}"
                                                            placeholder="Name" />

                                                        <!--end::Input-->
                                                        <!--begin::Description-->
                                                        <div class="text-muted fs-7">A product name is
                                                            required and recommended to be unique.</div>
                                                        <!--end::Description-->
                                                    </div>
                                                    <!--end::Input group-->
                                                </div>
                                            <!--begin::Input group-->
                                                <div class="mt-5">
                                                    <!--begin::Label-->
                                                    <label class="form-label required"><strong>Description
                                                            </strong></label>
                                                    <!--end::Label-->
                                                    <textarea name="description" id="kt_docs_ckeditor_classic_ar">{{ old('description') ? old('description') : '' }}</textarea>
                                                </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::General options-->
                                    <!--begin::Media-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Media</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-2">
                                                <input type="file" id="imageInput" name="logo[]" multiple>
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">Set the product media gallery.</div>

                                            <div id="imageContainer"></div>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Media-->

                                    <!--begin::Inventory-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Inventory</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">SKU</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" name="sku" class="form-control mb-2"
                                                    placeholder="SKU Number" id="sku" value="{{ Request::old('sku') }}" />
                                                <!--end::Input-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Enter the product SKU.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">Quantity</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="number" min="0" name="quantity"
                                                    class="form-control mb-2" placeholder="Quantity Number"
                                                    value="{{ Request::old('quantity') }}" />
                                                <!--end::Input-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Enter the product quantity.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->


                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Inventory-->
                                    <!--begin::Warehouse-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Warehouse</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Warehouse form-->
                                            <div id="kt_ecommerce_add_product_shipping">
                                                <!--begin::Input group-->
                                                <div class="mb-10 fv-row">
                                                    <!--begin::Label-->
                                                    <label class="required form-label">Warehouse</label>
                                                    <!--end::Label-->
                                                    <!--begin::Editor-->

                                                    <select class="form-select" name="warehouse_id"
                                                        data-control="select2" data-placeholder="Select an option"
                                                        id="warehouse_id">
                                                        <option value="{{ $warehouseIsInternal->id }}"
                                                            {{ $warehouseIsInternal->id == old('warehouse_id') ? 'selected' : '' }}>
                                                            {{ $warehouseIsInternal->name }}
                                                        </option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                {{ $warehouse->id == old('warehouse_id') ? 'selected' : '' }}>
                                                                {{ $warehouse->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <!--end::Editor-->
                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set Warehouse.</div>
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--end::Warehouse form-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Warehouse-->

                                    <!--begin::Shipping-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Shipping</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Shipping form-->
                                            <div id="kt_ecommerce_add_product_shipping">
                                                <!--begin::Input group-->
                                                <div class="mb-10 fv-row">
                                                    <!--begin::Label-->
                                                    <label class="form-label required">Weight</label>
                                                    <!--end::Label-->
                                                    <div class="input-group mb-3">
                                                        <input type="number" name="weight" min="0"
                                                            step="0.01" class="form-control mb-2" id="weight"
                                                            placeholder="Product weight" value="{{ old('weight') }}" />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="basic-addon2">KG</span>
                                                        </div>
                                                    </div>

                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set a product weight in kilograms (kg).
                                                    </div>
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--end::Shipping form-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Shipping-->

                                    <!--begin::Pricing-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Pricing</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">Price</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="number" min="0" step=".01" name="supplier_price_cost"
                                                    class="form-control mb-2" placeholder="Price" id="supplier_price_cost"
                                                    value="{{ Request::old('supplier_price_cost') }}" />
                                                <input  type="hidden" min="0"  step=".01" id="cost_price" name="cost_price" class="form-control mb-2" placeholder="Price" value="{{ old('supplier_price_cost') }}"  />

                                                <!--end::Input-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set the product price.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Pricing-->
                                </div>
                            </div>
                            <!--end::Tab pane-->
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Promotion</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-4">
                                            <!--begin::Input group-->
                                            <div class="fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label">Recommended for you</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <div class="form-check form-check-custom form-check-solid mb-2">
                                                    <input class="form-check-input"
                                                        {{ old('is_recommended') == 1 ? 'checked' : '' }} type="radio"
                                                        value="1" name="is_recommended" />
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid mb-2">
                                                    <input class="form-check-input"
                                                        {{ old('is_recommended') == 0 ? 'checked' : '' }} type="radio"
                                                        value="0" name="is_recommended" />
                                                    <label class="form-check-label">No</label>
                                                </div>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>

                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Discount</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="fs-6 fw-semibold mb-2">Discount Type
                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Select a discount type that will be applied to this product">
                                                        <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                    </span></label>
                                                <!--End::Label-->
                                                <!--begin::Row-->
                                                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9"
                                                    data-kt-buttons="true"
                                                    data-kt-buttons-target="[data-kt-button='true']">
                                                    <!--begin::Col-->
                                                    <div class="col">
                                                        <!--begin::Option-->
                                                        <label
                                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary active d-flex text-start p-6"
                                                            data-kt-button="true">
                                                            <!--begin::Radio-->
                                                            <span
                                                                class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                                <input class="form-check-input" type="radio"
                                                                    name="is_discount" value="0"
                                                                    checked="checked" id="no_discount_radio"/>
                                                            </span>
                                                            <!--end::Radio-->
                                                            <!--begin::Info-->
                                                            <span class="ms-5">
                                                                <span class="fs-4 fw-bold text-gray-800 d-block"
                                                                    style="color: #000 !important;">No Discount</span>
                                                            </span>
                                                            <!--end::Info-->
                                                        </label>
                                                        <!--end::Option-->
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col">
                                                        <!--begin::Option-->
                                                        <label
                                                            class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6"
                                                            data-kt-button="true">
                                                            <!--begin::Radio-->
                                                            <span
                                                                class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                                <input class="form-check-input" type="radio"
                                                                    name="is_discount" value="1" />
                                                            </span>
                                                            <!--end::Radio-->
                                                            <!--begin::Info-->
                                                            <span class="ms-5">
                                                                <span class="fs-4 fw-bold text-gray-800 d-block"
                                                                    style="color: #000 !important;">Fixed Price</span>
                                                            </span>
                                                            <!--end::Info-->
                                                        </label>
                                                        <!--end::Option-->
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Row-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="d-none mb-10 fv-row"
                                                id="kt_ecommerce_add_product_discount_percentage">
                                                <!--begin::Label-->
                                                <label class="form-label">Set Discount Percentage</label>
                                                <!--end::Label-->
                                                <!--begin::Slider-->
                                                <div class="d-flex flex-column text-center mb-5">
                                                    <div class="d-flex align-items-start justify-content-center mb-7">
                                                        <span class="fw-bold fs-3x"
                                                            id="kt_ecommerce_add_product_discount_label">0</span>
                                                        <span class="fw-bold fs-4 mt-1 ms-2">%</span>
                                                    </div>
                                                    <div id="kt_ecommerce_add_product_discount_slider" class="noUi-sm">
                                                    </div>
                                                </div>
                                                <!--end::Slider-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a percentage discount to be applied on
                                                    this product.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="d-none mb-10 fv-row" id="kt_ecommerce_add_product_discount_fixed">
                                                <!--begin::Label-->
                                                <label class="form-label required">Price After Discount</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="number" name="priceAfterDiscount" min="1"
                                                step="0.01" class="form-control mb-2"
                                                    placeholder="Price After Discount"
                                                    value="{{ Request::old('priceAfterDiscount') }}" id="price_after_discount_input"/>
                                                <!--end::Input-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set product price after descount</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                    </div>

                                    <!--begin::Meta options-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Meta Options</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label">Meta Tag Title</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" class="form-control mb-2" name="meta_title"
                                                    placeholder="Meta tag name" />
                                                <!--end::Input-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a meta tag title. Recommended to be simple
                                                    and precise keywords.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label">Meta Tag Description</label>
                                                <!--end::Label-->
                                                <!--begin::Editor-->
                                                <textarea name="product_meta_description" id="product_meta_description">{{ old('product_meta_description') }}</textarea>

                                                <!--end::Editor-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a meta tag description to the product for
                                                    increased SEO ranking.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div>
                                                <!--begin::Label-->
                                                <label class="form-label">Meta Tag Keywords</label>
                                                <!--end::Label-->
                                                <!--begin::Editor-->
                                                <input id="kt_ecommerce_add_product_meta_keywords"
                                                    name="kt_ecommerce_add_product_meta_keywords"
                                                    class="form-control mb-2" />
                                                <!--end::Editor-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a list of keywords that the product is
                                                    related to. Separate the keywords by adding a comma
                                                    <code>,</code>between each keyword.
                                                </div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Meta options-->
                                </div>
                            </div>
                            <!--end::Tab pane-->
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_add_product_variations" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-5">

                                    <!--begin::Variations-->
                                    <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input variation-switcher" type="checkbox"  name="has_variants" value="1"/>
                                                <span class="form-check-label">
                                                    Have Variation
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    </div>

                                    <div id="alert"></div>

                                    <!--begin::Variations-->
                                    <div class="card-body">
                                        @if($errors->any() && old('variants') && old('has_variants'))
                                            <auto-complete
                                                :suggestion-data="{{ $attributes }}"
                                                :existing-attributes="{{ old('attributes_data') ?? [] }}"
                                                :existing-variants="{{ json_encode(old('variants')) }}"
                                                :edit-mode="true"
                                            ></auto-complete>
                                        @else
                                            <auto-complete
                                            :suggestion-data="{{ $attributes }}"
                                            :editMode="false"
                                            ></auto-complete>
                                        @endif
                                    </div>
                                <!--end::Variations-->

                                </div>
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a href="{{ route('supplier.product.create') }}" id="kt_ecommerce_add_product_cancel"
                                class="btn btn-light me-5">Cancel</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                <span class="indicator-label">Save Changes</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                    </div>
                    <!--end::Main column-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

    <input type="hidden" name="MinPrice" />
    <input type="hidden" name="totalQuantity" />
    <input type="hidden" name="firstSku" />

    <div class="dropzone d-none" id="kt_ecommerce_add_product_media">
    </div>
    <script>
        function myFunction() {
            var checkBox = document.getElementById("myCheck");
            var myCheckFalse = document.getElementById("myCheckFalse");
            var text = document.getElementById("text");

            if (checkBox.checked == true) {
                text.style.display = "block";
            }

            if (myCheckFalse.checked == true) {
                text.style.display = "none";
            }
        }
    </script>


@endsection


@section('second-sidebar')
    @include('supplier::layouts.sidebar')
@endsection
@push('scripts')

    <script>
        var hasVariations = @json(old('has_variants') ? true : false);
    </script>
    <script src="{{ asset('dashboard2') }}/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
    <script src="{{ asset('dashboard2') }}/assets/js/custom/apps/ecommerce/catalog/save-product.js"></script>
    <script src="{{ asset('dashboard2') }}/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>
    <script src="{{ asset('dashboard2') }}/assets/js/product/variations.js"></script>

    <script>
        $(document).ready(function () {
            if(hasVariations) {
                $('.variation-switcher').prop('checked', true).trigger('change');
            }
        });
    </script>

    @foreach (language() as $lang)
        <script>
            ClassicEditor
                .create(document.querySelector('#kt_docs_ckeditor_classic_{{ $lang->code }}'))
                .then(editor => {
                    // console.log(editor);
                })
                .catch(error => {
                    // console.error(error);
                });
        </script>
    @endforeach
    <script>
        ClassicEditor
            .create(document.querySelector('#product_meta_description'))
            .then(editor => {
                // console.log(editor);
            })
            .catch(error => {
                // console.error(error);
            });
    </script>

    <script>
        $("#imageInput").on("change", (event) => {
            $("#imageContainer").html(null);
            const files = event.target.files;
            for (const file of files) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const img = document.createElement("img");
                    img.width = 100;
                    img.style.margin = "25px";
                    img.classList.add("remove-image");
                    img.src = event.target.result;
                    $("#imageContainer").append(img);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        function suggestCategory() {
            Swal.fire({
                title: 'Suggest a Category',
                html: '<label for="englishCategory" class="mt-4">English Category Name</label>' +
                    '<input id="englishCategory" class="swal2-input" placeholder="" pattern="[A-Za-z- ]+" minlength="2" maxlength="50" required>' +
                    '<label for="arabicCategory" class="mt-4">Arabic Category Name</label>' +
                    '<input id="arabicCategory" class="swal2-input" placeholder="" pattern="[A-Za-z- ]+" minlength="2" maxlength="50" required>',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const englishCategoryName = Swal.getPopup().querySelector('#englishCategory').value;
                    const arabicCategoryName = Swal.getPopup().querySelector('#arabicCategory').value;

                    // Validation
                    if (!englishCategoryName || !arabicCategoryName) {
                        Swal.showValidationMessage('Please enter both category names.');
                        return false;
                    }

                    const validationRegex = /^[A-Za-zأ-ي\- ]+$/;
                    if (!validationRegex.test(englishCategoryName) || !validationRegex.test(arabicCategoryName)) {
                        Swal.showValidationMessage('Category names should only contain letters, spaces, and "-" symbol.');
                        return false;
                    }


                    // Min length and Max length validation
                    if (englishCategoryName.length < 2 || arabicCategoryName.length < 2 ||
                        englishCategoryName.length > 50 || arabicCategoryName.length > 50) {
                        Swal.showValidationMessage('Category names should be between 2 and 50 characters.');
                        return false;
                    }

                    return {
                        englishCategoryName,
                        arabicCategoryName
                    };
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const {
                        englishCategoryName,
                        arabicCategoryName
                    } = result.value;
                    submitSuggestion(englishCategoryName, arabicCategoryName);
                }
            });
        }

        async function submitSuggestion(englishCategoryName, arabicCategoryName) {
            var csrfToken = "{{ csrf_token() }}";

            Swal.fire({
                title: 'Submitting...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            try {
                const response = await $.ajax({
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    url: `{{ route('supplier.storeCategoryBySupplier') }}`,
                    data: {
                        _token: csrfToken,
                        'name[en]': englishCategoryName,
                        'name[ar]': arabicCategoryName,
                    },
                })

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Your suggestion has been sent to the responsible team successfully',
                });
            } catch (error) {

                if (error.status === 422) {
                    const errors = error.responseJSON.errors;
                    if (errors) {
                        const firstErrorKey = Object.keys(errors)[0];
                        const firstErrorMessage = errors[firstErrorKey][0];
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: firstErrorMessage,
                        });
                        return;
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to submit suggestion. Please try again later.',
                });
            }
        }


        $('#suggestCategory').click(function() {
            suggestCategory();
        });

        // Listen for changes in the radio buttons
    $('input[name="is_discount"]').change(function () {
        if ($('#no_discount_radio').prop('checked')) {
            $('#price_after_discount_input').val(null);
            // updateCalculations();
            // calculateCommission();
            // updateCalculations();
        } else {
            // updateCalculations();
        }
    });
    </script>
    <script>
        // Listen for changes in the radio buttons
        $('input[name="is_discount"]').change(function () {
            if ($('#no_discount_radio').prop('checked')) {
                $('#price_after_discount_input').val(null);
                // updateCalculations();
                // calculateCommission();
                // updateCalculations();
            } else {
                // updateCalculations();
            }
        });
    </script>

    <script>
        var getAttributeValidationRoute = "{{ route('ajax.attributes') }}";

        function toggleAttributesSelect(isChecked) {
            if (isChecked) {
                if(! checkCostPriceAndSKU()) {
                    return;
                }

                $("#variant-container").show();
                $("#variants").show();
            } else {
                $("#variant-container").hide();
                $("#variants").html(null);
                $("#attributes").val([]).trigger('change');
                $("#variants").hide();
                $("#variants select").val([]).trigger('change');

            }
        }

        function checkCostPriceAndSKU() {
            var costPrice = $("#generic_cost_price").val();
            var sku = $("#generic_sku").val();
            if (costPrice && sku) {
                $("#validationMessage").hide();

                return true;
            } else {
                $("#validationMessage").show();

                return false;
            }
        }

        $("#generic_cost_price, #generic_sku").on('input', checkCostPriceAndSKU);

        $("#open_variation").on('change', function() {

            var isChecked = $(this).is(":checked");
            toggleAttributesSelect(isChecked);
        });

        $("#attributes").on('change', function() {
            var isChecked = $("#open_variation").is(":checked");
            if (isChecked) {
                var attributes = $(this).val();
                $.ajax({
                    url: getAttributeValidationRoute,
                    type: 'GET',
                    data: {
                        'attributes': attributes
                    },
                    success: function(data) {
                        $("#variants").html(data);
                    },
                    error: function(request, error) {
                        console.log('Error');
                        console.log(JSON.stringify(request));
                    }
                });
            }
        });

        toggleAttributesSelect($("#open_variation").is(":checked"));
    </script>

<script>
    $(document).ready(function () {
        // Listen for changes in the supplier_price_cost input
        $('#supplier_price_cost').on('input', function () {
            // Update the cost_price input with the new value
            $('#cost_price').val($(this).val());
        });
    });
</script>
@endpush
