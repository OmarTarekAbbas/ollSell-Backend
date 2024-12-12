@extends($layout)


@section('title', 'Edit Product - ' . $data->name?->value)
@push('styles')
    <style>
        .logo-images {
            border-radius: 5px;
            margin: 5px;
        }

        .logo-images:hover {
            opacity: 0.5;
        }


        #select2-warehouse_id-results li:first-child {
            background: #e9e9e9;
        }
    </style>
@endpush
@section('content')

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
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Update
                            Product Form</h1>
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
                <form class="form d-flex flex-column flex-lg-row" action="{{ route('product.update', $data->id) }}"
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

                                <!--begin::Image input-->
                                <div class="image-input image-input-outline" data-kt-image-input="true"
                                     style="background-image: url(/assets/media/svg/avatars/blank.svg)">
                                    <!--begin::Image preview wrapper-->
                                    <div class="image-input-wrapper w-175px h-175px"
                                         style="background-image: url('@if (count($data->thumbnail()->get())) {{ getFile($data->thumbnail()->get()[0]->file, 'images', getFileNameServer($data->thumbnail()->get()[0])) }} @else {{ asset('dashboard') }}/assets/media/svg/files/blank-image.svg @endif')">
                                    </div>
                                    <!--end::Image preview wrapper-->

                                    <!--begin::Edit button-->
                                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                           data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                           data-bs-dismiss="click" title="Change thumbnail">
                                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span
                                                    class="path2"></span></i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="thumbnail" accept=".png, .jpg, .jpeg"/>
                                        <input type="hidden" name="thumbnail_remove"/>
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Edit button-->

                                    <!--begin::Cancel button-->
                                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                          data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                          data-bs-dismiss="click" title="Cancel thumbnail">
                                    <i class="ki-outline ki-cross fs-3"></i>
                                </span>
                                    <!--end::Cancel button-->

                                    <!--begin::Remove button-->
                                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                          data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                          data-bs-dismiss="click" title="Remove thumbnail">
                                    <i class="ki-outline ki-cross fs-3"></i>
                                </span>
                                    <!--end::Remove button-->
                                </div>
                                <!--end::Image input-->
                                <!--egin::Description-->
                                <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and
                                    *.jpeg
                                    image files are accepted
                                </div>
                                <!--end::Description-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Thumbnail settings-->
                        <!--begin::Barcode settings-->
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
                                    <input type="text" name="barcode" value="{{ old('barcode') ?? $data->barcode }}"
                                           pattern="[A-Za-z0-9-]{5,50}"
                                           oninput="this.value = this.value.replace(/[^A-Za-z0-9-]/g, '').replace(/(\..*)\./g, '$1');"
                                           title="Letters, numbers, and hyphen only, 5-50 characters"
                                           class="form-control"/>
                                </div>
                                <!-- Additional description and details if needed -->
                                <div class="text-muted fs-7">
                                    Set the product barcode. Letters, numbers, and hyphen only. 5-50 characters.
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Barcode settings-->
                        <!--begin::Status-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="required">Status</h2>
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
                                        id="kt_ecommerce_add_product_status_select">
                                    <option></option>
                                    <option value="1" {{ old('status', $data->status) == 1 ? 'selected' : '' }}>
                                        Live
                                    </option>
                                    <option value="0" {{ old('status', $data->status) == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                <!--end::Select2-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Set the product status.</div>
                                <!--end::Description-->
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
                                    @php
                                        $selectedCategories = $data->categories->pluck('id')->toArray();
                                    @endphp
                                    @foreach ($categories as $value)
                                        @php
                                            $isFavorite = in_array($value->id, $selectedCategories);
                                        @endphp
                                        <option data-commission-percentage="{{ $value->commission }}"
                                                {{ $isFavorite ? 'selected' : '' }} value="{{ $value->id }}">
                                            {{ $value->name->value }}
                                        </option>
                                    @endforeach
                                </select>


                                <!--end::Select2-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7 mb-7">Add product to a category.</div>
                                <!--end::Description-->
                                <!--end::Input group-->
                                <!--begin::Button-->
                                <a href="{{ route('category.create') }}" class="btn btn-light-primary btn-sm mb-10"
                                   target="_blank">
                                    <i class="ki-outline ki-plus fs-2"></i>Create new category</a>
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
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                   href="#kt_ecommerce_add_related_products">Related Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                   href="#kt_ecommerce_add_product_dropshippers">Related Dropshipper</a>
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
                                                    <label class="required form-label">Product Name</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="text" name="name" class="form-control mb-2"
                                                           placeholder="Product name"
                                                           value="{{  old('name' , @$data->nameValue(2)) }}"/>
                                                    <!--end::Input-->
                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">A product name is required and
                                                        recommended to be unique.
                                                    </div>
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--begin::Input group-->
                                            <div class="mt-5">
                                                <!--begin::Label-->
                                                <label class="form-label required"><strong>Description</strong></label>
                                                <!--end::Label-->
                                                <textarea name="description"
                                                          id="kt_docs_ckeditor_classic_ar"> {!! old('description', @$data->descriptionValue(2)) !!}</textarea>
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
                                                <h2>WMS</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">


                                            <!--begin::Input group-->
                                            <div class="fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label">IS WMS</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <div class="form-check form-check-custom form-check-solid mb-2">
                                                    <input class="form-check-input" type="radio" value="1"
                                                           name="is_wms" {{ old('is_wms', $data->is_wms) == 1 ? 'checked' : '' }} />
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid mb-2">
                                                    <input class="form-check-input" type="radio" value="0"
                                                           name="is_wms" {{ old('is_wms', $data->is_wms) == 0 ? 'checked' : '' }} />
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
                                                <h2>Media</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-2">
                                                <input type="file" id="imageInput" name="logo[]" multiple>
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">Set the product media gallery.</div>

                                            <div id="imageContainer" style="display: inline">
                                                @foreach ($data->logo as $image)
                                                    <img class="logo-images"
                                                         src="{{ getFile($image->file, 'images', getFileNameServer($image)) }}"
                                                         title="Click To Remove" style="width:100px;"
                                                         onclick="deleteImage('{{ $image->id }}', this)" alt="Image">
                                                @endforeach
                                            </div>
                                            <div style="display: inline" id="newImageInput"></div>
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
                                                <input type="text" name="sku" id="sku" class="form-control mb-2"
                                                       placeholder="SKU Number" value="{{ old('sku', $data->sku)}}"/>
                                                <!--end::Input-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Enter the product SKU.</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">Quantity</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="number" min="0" name="quantity" class="form-control mb-2"@if($data->is_wms) disabled @endif
                                                       placeholder="Quantity Number"
                                                       value="{{ $data->quantity}}"/>
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
                                                        <option></option>
                                                        <option {{ (old('warehouse_id') && $warehouseIsInternal->id == old('warehouse_id')) ? "selected" : ($warehouseIsInternal->id == $data->warehouse_id ? "selected" : '') }} value="{{ $warehouseIsInternal->id }}">{{$warehouseIsInternal->name}}</option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option {{ (old('warehouse_id') && $warehouse->id == old('warehouse_id')) ? "selected" : ($warehouse->id == $data->warehouse_id ? "selected" : '') }} value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
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
                                                    <!--begin::Editor-->
                                                    <div class="input-group mb-3">
                                                        <input type="number" name="weight" id="weight" min="0"
                                                               step="0.01" class="form-control mb-2"
                                                               placeholder="Product weight" value="{{$data->weight}}"/>

                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="basic-addon2">KG</span>
                                                        </div>
                                                    </div>

                                                    <!--end::Editor-->
                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set a product weight in kilograms
                                                        (kg).
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
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Custom Commission</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10 row fv-row">
                                                <div class="card-title">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input custam_commission"
                                                               type="checkbox" name="custam_commission"
                                                               @if ($data->custam_commission == 1)
                                                                   @checked(true)

                                                               @endif

                                                               value="1"/>
                                                        <span class="form-check-label">
                                                    Custom Commission
                                                </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                            <div class="mb-10 row fv-row">
                                                <div class="col">
                                                    <!--begin::Label-->
                                                    <label class="required form-label">Supplier Cost</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="number" min="1" step=".01" name="supplier_price_cost"
                                                           class="form-control mb-2" placeholder="Supplier Price"
                                                           value="{{ old('supplier_price_cost', $data->supplier_price_cost) }}"/>
                                                    <!--end::Input-->
                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set the product supplier price.</div>
                                                    <!--end::Description-->
                                                </div>

                                                <div class="col-sm">
                                                    <!--begin::Label-->
                                                    <label class="required form-label">VAT</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="number" min="0" readonly name="main_supplier_price_vat"
                                                           class="form-control mb-2" id="supplier_price_vat"/>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row" id="pricing-map">
                                                <div class="row">
                                                    <div class="col-sm">
                                                        <!--begin::Label-->
                                                        <label class="required form-label">Supplier Price</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="number" min="0" readonly name="supplier_price"
                                                               class="form-control mb-2" id="supplier_price"/>
                                                        <!--end::Input-->
                                                    </div>
                                                    {{-- <div class="col-sm"> --}}
                                                    <!--begin::Label-->
                                                    {{-- <label class="required form-label">VAT</label> --}}
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="hidden" min="0" readonly name="supplier_price_vat"
                                                           class="form-control mb-2" id="supplier_price_vat"/>
                                                    <!--end::Input-->
                                                    {{-- </div> --}}
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm">

                                                        <!--begin::Label-->
                                                        <label class="required form-label">Commission</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="number" id="commission"
                                                               @if ($data->custam_commission == 0)
                                                                   @readonly(true)
                                                               @endif
                                                               name="commission" class="form-control mb-2" step="0.01"
                                                               max="1000.00" min="0.00"
                                                               value="{{ old('commission') ?? $data->commission }}"/>
                                                        <!--end::Input-->
                                                    </div>
                                                    <div class="col-sm">
                                                        <!--begin::Label-->
                                                        <label class="required form-label">VAT</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="number" min="0" readonly name="vat_commission"
                                                               class="form-control mb-2"
                                                               value="{{ old('vat_commission') ?? ($data->vat_commission ?? 0)}}"/>
                                                        <!--end::Input-->
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm">
                                                        <!--begin::Label-->
                                                        <label class="required form-label">Cost price</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="number" readonly name="cost_price"
                                                               class="form-control mb-2" id="cost_price"/>
                                                        <!--end::Input-->
                                                    </div>

                                                </div>

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
                                    <!--begin::Promotion-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Promotion</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">


                                            <!--begin::Input group-->
                                            <div class="fv-row">
                                                <!--begin::Label-->
                                                <label class="form-label">Recommended for you</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <div class="form-check form-check-custom form-check-solid mb-2">
                                                    <input class="form-check-input" type="radio" value="1"
                                                           name="is_recommended" {{ old('is_recommended', $data->is_recommended) == 1 ? 'checked' : '' }} />
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid mb-2">
                                                    <input class="form-check-input" type="radio" value="0"
                                                           name="is_recommended" {{ old('is_recommended', $data->is_recommended) == 0 ? 'checked' : '' }} />
                                                    <label class="form-check-label">No</label>
                                                </div>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Promotion-->

                                    <!--begin::Discount-->
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
                                                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary  d-flex text-start p-6 {{ old('is_discount', $data->is_discount) == 0 ? 'active' : '' }}"
                                                               data-kt-button="true">
                                                            <!--begin::Radio-->
                                                            <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                            <input class="form-check-input" id="no_discount_radio"
                                                                   type="radio" name="is_discount" value="0" {{ old('is_discount', $data->is_discount) == 0 ? 'checked' : '' }} />
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
                                                    {{-- <div class="col">
                                                                <!--begin::Option-->
                                                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                                                                    <!--begin::Radio-->
                                                                    <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                                        <input class="form-check-input" id="discount_radio" type="radio" name="is_discount" value="2" />
                                                                    </span>
                                                                    <!--end::Radio-->
                                                                    <!--begin::Info-->
                                                                    <span class="ms-5">
                                                                        <span class="fs-4 fw-bold text-gray-800 d-block">Percentage %</span>
                                                                    </span>
                                                                    <!--end::Info-->
                                                                </label>
                                                                <!--end::Option-->
                                                            </div>
                                                            <!--end::Col--> --}}
                                                    <!--begin::Col-->
                                                    <div class="col">
                                                        <!--begin::Option-->
                                                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary  d-flex text-start p-6 {{ old('is_discount', $data->is_discount) == 1 ? 'active' : '' }}"
                                                               data-kt-button="true">
                                                            <!--begin::Radio-->
                                                            <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                            <input class="form-check-input" id="fixed_price_radio"
                                                                   type="radio" name="is_discount" value="1" {{ old('is_discount', $data->is_discount) == 1 ? 'checked' : '' }} />
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
                                                    <div id="kt_ecommerce_add_product_discount_slider"
                                                         class="noUi-sm"></div>
                                                </div>
                                                <!--end::Slider-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a percentage discount to be applied on
                                                    this product.
                                                </div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="{{ old('is_discount', $data->is_discount) == 1 ? '' : 'd-none' }} row mb-10 fv-row"
                                                 id="kt_ecommerce_add_product_discount_fixed">
                                                <div class="col">
                                                    <!--begin::Label-->
                                                    <label class="form-label required">Supplier Price After
                                                        Discount</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input id="price_after_discount_input" type="number" min="1"
                                                           step="0.01" name="priceAfterDiscount" @if(!$data->is_discount) disabled @endif
                                                           class="form-control mb-2"
                                                           placeholder="Supplier Price After Discount"
                                                           value="{{ $data->priceAfterDiscount }}"/>
                                                    <!--end::Input-->
                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set supplier product price after
                                                        discount
                                                    </div>
                                                    <!--end::Description-->
                                                </div>

                                                <div class="col-sm">
                                                    <!--begin::Label-->
                                                    <label class="required form-label">VAT</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="number" min="0" readonly
                                                           name="discount_supplier_price_vat" class="form-control mb-2"
                                                           id="discount_supplier_price_vat"/>
                                                    <!--end::Input-->
                                                </div>
                                            </div>

                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::discount-->

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
                                                       placeholder="Meta tag name"
                                                       value="{{ old('meta_title', $data->meta_title)}}"/>
                                                <!--end::Input-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a meta tag title. Recommended to be
                                                    simple and precise keywords.
                                                </div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label">Meta Tag Description</label>
                                                <!--end::Label-->
                                                <!--begin::Editor-->
                                                <textarea name="product_meta_description"
                                                          id="product_meta_description">{{ old('product_meta_description', $data->product_meta_description)}}</textarea>
                                                <!--end::Editor-->
                                                <!--begin::Description-->
                                                <div class="text-muted fs-7">Set a meta tag description to the product
                                                    for increased SEO ranking.
                                                </div>
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
                                                       name="product_meta_keywords" class="form-control mb-2"
                                                       value="{{ old('product_meta_keywords', $data->product_meta_keywords)}}"/>
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
                                <div class="d-flex flex-column gap-7 gap-lg-10">


                                    <!--begin::Variations-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input variation-switcher" type="checkbox"
                                                           name="has_variants" value="1"/>
                                                    <span class="form-check-label">
                                                            Have Variation
                                                        </span>
                                                </label>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                    </div>

                                    <div id="alert">
                                    </div>

                                    <!--begin::Variations-->
                                    <div class="card-body">
                                        @if($errors->any() && old('variants') && old('has_variants'))
                                            <auto-complete :suggestion-data="{{ $attributes }}"
                                                           :existing-attributes="{{ old('attributes_data') ?? [] }}"
                                                           :existing-variants="{{ json_encode(old('variants')) }}"
                                                           :edit-mode="true"></auto-complete>
                                        @else
                                            <auto-complete :suggestion-data="{{ $attributes }}"
                                                           :existing-attributes="{{ $existingAttributes ?? [] }}"
                                                           :existing-variants="{{ $variants }}"
                                                           :edit-mode="true"></auto-complete>
                                        @endif
                                    </div>
                                    <!--end::Variations-->

                                </div>
                            </div>
                            <div class="tab-pane fade" id="kt_ecommerce_add_related_products" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-5">

                                    <!--begin::Variations-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <span class="form-check-label">
                                                      Select Product
                                                    </span>
                                                </label>

                                            </div>
                                            <select id="related_products" name="related_products[]"
                                                    class="form-select form-select-solid form-select-lg fw-semibold"
                                                    data-mce-placeholder="" multiple>
                                                @foreach($data->related_products as $related)
                                                    <option selected value="{{$related->related_product_id}}"> {{$related->related_product->name->value ?? ""}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <!--end::Card header-->
                                    </div>


                                    <!--begin::Variations-->
                                    <div class="card-body" id="main-related-products">

                                    </div>
                                    <!--end::Variations-->

                                </div>
                                <!--end::Tab pane-->
                            </div>
                            <div class="tab-pane fade" id="kt_ecommerce_add_product_dropshippers" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-5">

                                    <!--begin::Variations-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <span class="form-check-label">
                                                      Select Dropshipper
                                                    </span>
                                                </label>

                                            </div>
                                            <select id="product_dropshippers" name="product_dropshippers[]"
                                                    class="form-select form-select-solid form-select-lg fw-semibold"
                                                    data-mce-placeholder="" multiple>
                                                @foreach($data->product_dropshippers as $related)
                                                    <option selected value="{{$related->dropshipper_id}}"> {{$related->dropshipper->email}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <!--end::Card header-->
                                    </div>


                                    <!--begin::Variations-->
                                    <div class="card-body" id="main-related-products">

                                    </div>
                                    <!--end::Variations-->

                                </div>
                                <!--end::Tab pane-->
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a href="{{ route('product.create') }}" id="kt_ecommerce_add_product_cancel"
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

    <input type="hidden" name="MinPrice"/>
    <input type="hidden" name="totalQuantity"/>
    <input type="hidden" name="firstSku"/>

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
    @include('mastercatalog::layouts.sidebar')
@endsection


@push('scripts')

    <script src="{{ asset('dashboard2') }}/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
    <script src="{{ asset('dashboard2') }}/assets/js/custom/apps/ecommerce/catalog/save-product.js"></script>
    <script src="{{ asset('dashboard2') }}/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>
    <script src="{{ asset('dashboard2') }}/assets/js/product/variations.js"></script>
    <script>
        var vatProduct = @json(setting('vat_product'));
        var hasVariations = @json(old('has_variants') || $hasVariations ? true : false);
        var custam_commission = @json(old('custam_commission') ? true : false);
        $(document).ready(function () {
            if (custam_commission) {
                $('.custam_commission').prop('checked', true).trigger('change');
            }
            if (hasVariations) {
                $('.variation-switcher').prop('checked', true).trigger('change');
            } else {
                $('.variation-switcher').prop('checked', false);
                document.getElementById(
                    "kt_ecommerce_add_product_submit"
                ).disabled = false;
            }
            ClassicEditor
                .create(document.querySelector('#kt_docs_ckeditor_classic_ar'))
                .then(editor => {
                    console.log(editor);
                })
                .catch(error => {
                    console.error(error);
                });
            ClassicEditor
                .create(document.querySelector('#product_meta_description'))
                .then(editor => {
                    console.log(editor);
                })
                .catch(error => {
                    console.error(error);
                });
        });
        $('#kt_docs_repeater_basic').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
        var getAttributeValidationRoute = "{{ route('ajax.attributes') }}"

        $("#attributes").on('change', function () {
            var attributes = $(this).val();
            $.ajax({
                url: getAttributeValidationRoute,
                type: 'GET',
                data: {
                    'attributes': attributes
                },
                success: function (data) {
                    $("#variants").html(data);
                },
                error: function (request, error) {
                    console.log('Error')
                    console.log(JSON.stringify(request));
                }
            });
        });

        function deleteImage(imageId, el) {
            $.ajax({
                url: "{{ route('product.delete_image') }}",
                type: 'GET',
                data: {
                    'product_id': "{{ $data->id }}",
                    'image_id': imageId
                },
                success: function (data) {
                    el.remove();
                },
                error: function (request, error) {
                    console.log('Error')
                    console.log(JSON.stringify(request));
                }
            });
        }

        $("#imageInput").on("change", (event) => {
            $("#newImageInput").html(null);
            const files = event.target.files;
            for (const file of files) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const img = document.createElement("img");
                    img.width = 100;
                    img.style.margin = "25px";
                    img.classList.add("remove-image");
                    img.src = event.target.result;
                    $("#newImageInput").append(img);
                };
                reader.readAsDataURL(file);
            }
        });
        $(document).ready(function () {
            const costPriceInput = document.querySelector('input[name="supplier_price_cost"]');
            const priceAfterDiscountInput = document.querySelector('input[name="priceAfterDiscount"]');
            const supplierPriceInput = document.querySelector('input[name="supplier_price"]');
            const commissionVatInput = document.querySelector('input[name="vat_commission"]');
            const commissionInput = document.querySelector('input[name="commission"]');
            const finalCostPriceInput = document.querySelector('input[name="cost_price"]');
            const supplierPriceVatInput = document.querySelector('input[name="supplier_price_vat"]');

            const mainSupplierPriceVatInput = document.querySelector('input[name="main_supplier_price_vat"]');
            const discountSupplierPriceVatInput = document.querySelector('input[name="discount_supplier_price_vat"]');

            costPriceInput.addEventListener('input', updatePrice);
            priceAfterDiscountInput.addEventListener('input', updatePrice);

            updateCalculations(false);
            commissionInput.value = @json($data->commission) ??
            0

            function updatePrice() {
                updateCalculations();
                calculateCommissionAndVat();
                updateCalculations();
            }


            function updateCalculations(calculateCommission = true) {
                const costPrice = parseFloat(costPriceInput.value) || 0;
                const priceAfterDiscount = parseFloat(priceAfterDiscountInput.value) || costPrice;

                // calculate vat for prices
                mainSupplierPriceVatInput.value = (costPrice * vatProduct).toFixed(2);
                discountSupplierPriceVatInput.value = priceAfterDiscountInput.value * vatProduct;

                const supplierPrice = priceAfterDiscount + (costPrice * vatProduct);
                supplierPriceInput.value = supplierPrice.toFixed(2);

                const supplierPriceVat = supplierPrice * vatProduct;
                supplierPriceVatInput.value = supplierPriceVat.toFixed(2);

                const commission = commissionInput.value || 0;

                // if (calculateCommission) {
                //     updateCommission(commission);
                // } else {
                commissionVatInput.value = (commissionInput.value * vatProduct).toFixed(2);
                // }

                const finalCostPrice = Number(supplierPrice) + Number(commissionInput.value) + Number(
                    commissionVatInput.value) ;
                finalCostPriceInput.value = finalCostPrice.toFixed(2);
            }

            function updateCommission(commission) {
                commissionInput.value = (costPriceInput.value * commission / 100).toFixed(2);
                alert(commissionInput.value)
                commissionVatInput.value = (commissionInput.value * vatProduct).toFixed(2);

                calculateCommissionAndVat()
            }

            function calculateCommissionAndVat() {
                var selectedCategoryIds = $('select[name="category_Ids[]"]').val();
                var costPrice = parseFloat($('#supplier_price').val()) || 0;

                if ((!selectedCategoryIds || selectedCategoryIds.length === 0) && $('input[name="custam_commission"]').prop('checked') == false) {
                    $('#commission').val(0);
                    return;
                }
                var totalCommission = 0;
                selectedCategoryIds.forEach(function (categoryId) {
                    var categoryOption = $('select[name="category_Ids[]"] option[value="' + categoryId +
                        '"]');
                    var categoryPercentage = categoryOption.data('commissionPercentage');
                    if (categoryPercentage) {
                        totalCommission = Math.max(totalCommission, categoryPercentage);
                    }
                });

                if (totalCommission > 0 && $('input[name="custam_commission"]').prop('checked') == false) {
                    var commissionValue = (costPrice * totalCommission / 100).toFixed(2);
                    $('#commission').val(commissionValue);
                }
            }

            $('select[name="category_Ids[]"]').change(function () {
                updateCalculations();
                calculateCommissionAndVat();
                updateCalculations();
            });

            // Listen for manual changes in the commission input
            $('#commission').on('input', function () {
                $(this).data('categoryPercentage', null);
                updateCalculations(false);
            });

            // Listen for changes in the cost price
            $('#supplier_price').on('change', function () {
                updateCalculations();
                calculateCommissionAndVat();
                updateCalculations();
            });


            $('#no_discount_radio').change(function () {
                if ($(this).is(':checked')) {
                    $('#price_after_discount_input').val('');
                    updateCalculations();
                }
            });

            $('input[name="is_discount"]').change(function () {
                if ($('#no_discount_radio').prop('checked')) {
                    $('#price_after_discount_input').val(null);
                    updateCalculations();
                    calculateCommissionAndVat();
                    updateCalculations();
                } else {
                    updateCalculations();
                }
            });

            $('input[name="custam_commission"]').change(function () {

                if ($('input[name="custam_commission"]').prop('checked')) {

                    $('#commission').removeAttr("readonly")
                    updateCalculations();
                    calculateCommissionAndVat();
                    updateCalculations();


                } else {

                    updateCalculations();
                    calculateCommissionAndVat();
                    updateCalculations();
                    $("#commission").attr("readonly", true);
                }
            });

            @if(old('priceAfterDiscount') || $data->priceAfterDiscount)
            // Open the "Fixed Price" tab
            document.getElementById('fixed_price_radio').click();
            $('#kt_ecommerce_add_product_discount_fixed').removeClass('d-none');
            // $('#price_after_discount_input').val(@json(old('priceAfterDiscount') || $data->priceAfterDiscount));
            updateCalculations();
            calculateCommissionAndVat();
            updateCalculations();
            // Add the active class to the "Fixed Price" label
            // document.getElementById('fixed_price_label').classList.add('active');
            // document.getElementById('no_discount_label').classList.remove('active');
            @else
            if ($('#no_discount_radio').prop('checked')) {
                // $('#price_after_discount_input').val(null);
                updateCalculations();
                calculateCommissionAndVat();
                updateCalculations();
            } else {
                updateCalculations();
            }
            @endif
            $('#related_products').select2({
                placeholder: "{{ 'select sku' }}...",
                ajax: {
                    url: "{{ route('product.search') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name.value
                                }
                            }),
                        };
                    },
                }
            });
            $('#product_dropshippers').select2({
                placeholder: "{{ 'select id or name' }}...",
                ajax: {
                    url: "{{ route('dropshipper.search') }}",
                    method: 'get',
                    dataType: 'json',
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.email
                                }
                            }),
                        };
                    },
                }
            });
        });
    </script>
@endpush
