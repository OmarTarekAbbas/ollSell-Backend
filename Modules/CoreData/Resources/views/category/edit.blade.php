@extends($layout)


@section('title', 'Category')

@section('content')

<!--begin::Basic info-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_category_create" aria-expanded="true" aria-controls="kt_category_create">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Category Details</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Content-->
    <div id="kt_account_settings_profile_details" class="collapse show">
        <!--begin::Form-->
        @include('dashboard.error.error')
        <form id="kt_category_edit_form" class="form" method="post" action="{{route('category.update',$data->id)}}" enctype="multipart/form-data">
            @csrf
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                <div class="row mb-6">
                    @foreach(language() as $lang)
                    <div class="col">
                        <!--begin::Label-->
                        <label class="col-form-label required fw-semibold fs-6">Name ({{$lang->name}})</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div>
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="fv-row">
                                    <input type="text" name="name[{{$lang->code}}]" value="{{$data->nameValue($lang)}}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name {{$lang->code}}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    @endforeach
                </div>

                <div class="row mb-6">
                    @foreach(language() as $lang)
                    <div class="col">
                        <!--begin::Label-->
                        <label class="col-form-label required fw-semibold fs-6">Meta Title ({{$lang->name}})</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div>
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="fv-row">
                                    <input type="text" name="metaTitle[{{$lang->code}}]" value="{{$data->metaTitleValue($lang)}}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Meta Title {{$lang->code}}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    @endforeach
                </div>


                <div class="row mb-6">
                    @foreach(language() as $lang)
                    <div class="col">
                        <!--begin::Label-->
                        <label class="col-form-label required fw-semibold fs-6">Meta Description ({{$lang->name}})</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div>
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="fv-row">
                                    <textarea type="text" name="metaDescription[{{$lang->code}}]" value="{{$data->metaDescriptionValue($lang)}}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Meta Description {{$lang->code}}" require>{{$data->metaDescriptionValue($lang)}}</textarea>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    @endforeach
                </div>
                <!--end::Input group-->

                <div class="row mb-6">

                    <div class="col">
                        <!--begin::Label-->
                        <label class="col-form-label fw-semibold fs-6">Parent Category</label>
                        <!--end::Label-->
                        <!--begin::Editor-->
                        <select class="form-select" name="parent_id" data-control="select2" data-placeholder="Select an option" id="parent_id">
                            <option value="0">No parent category</option>
                            @foreach($category as $value)
                            <option {{ old('parent_id', $data->parent_id) == $value->id ? 'selected' : ($data->parent_id == $value->id ? 'selected' : '') }} value="{{$value->id}}">
                                {{$value->name->value}}
                            </option>
                            @endforeach
                        </select>
                        <!--end::Editor-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Select a parent category.</div>
                        <!--end::Description-->
                    </div>

                    <div class="col">
                        <!--begin::Label-->
                        <label class="col-form-label fw-semibold fs-6 required">Commission</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div>
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="fv-row">
                                    <div class="input-group mb-3">
                                        <input type="text" name="commission" value="{{$data->commission}}" class="form-control" id="commission-input" placeholder="20" aria-label="Commission" aria-describedby="percentage-addon" required>
                                        <div class="input-group-append">
                                        <span class="input-group-text" id="percentage-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                </div>

                <!--begin::Card body-->
                <div id="kt_ecommerce_add_product_shipping">
                    <!--begin::Input group-->
                    <div class="mb-10 fv-row">
                        <label class="col-form-label fw-semibold fs-6">Meta Tags </label>
                        <input class="form-control" name="multiMeta" value="@foreach($data->metaCategory as $metaCategory)
                        {{$metaCategory->name}},
                        @endforeach" id="kt_tagify_1" />
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Warehouse form-->

                <!--begin::avatar settings-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Category Image</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body text-center pt-0">
                        <!--begin::Image input-->
                        <!--begin::Image input placeholder-->

                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(/assets/media/svg/avatars/blank.svg)">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-175px h-175px" style="background-image: url('@if(count($data->avatar()->get()))  {{ getFile($data->avatar()->get()[0]->file,'images',getFileNameServer($data->avatar()->get()[0])) }} @else {{ asset('dashboard') }}/assets/media/svg/files/blank-image.svg @endif')">
                            </div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Change avatar">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                                <!--begin::Inputs-->
                                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                                <!--end::Inputs-->
                            </label>
                            <!--end::Edit button-->

                            <!--begin::Cancel button-->
                            <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancel avatar">
                                <i class="ki-outline ki-cross fs-3"></i>
                            </span>
                            <!--end::Cancel button-->

                            <!--begin::Remove button-->
                            <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Remove avatar">
                                <i class="ki-outline ki-cross fs-3"></i>
                            </span>
                            <!--end::Remove button-->
                        </div>
                        <!--end::Image input-->
                        <!--egin::Description-->
                        <div class="text-muted fs-7">Set the category avatar image. Only *.png, *.jpg and *.jpeg
                            image files are accepted</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::avatar settings-->

            </div>
            <!--end::Input group-->
            <!--end::Card body-->
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{  route('category.index') }}" class="btn btn-light btn-active-light-primary me-2">Discard</a>
                <button type="submit" class="btn btn-primary" id="kt_category_create_submit">Save Changes
                </button>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
</div>
<!--end::Basic info-->

@endsection

@section('second-sidebar')
@include('mastercatalog::layouts.sidebar')
@endsection

@push('scripts')

<script>
    var route = "{{ route('category.index') }}";
    // The DOM elements you wish to replace with Tagify
    var input1 = document.querySelector("#kt_tagify_1");
    var input2 = document.querySelector("#kt_tagify_2");

    // Initialize Tagify components on the above inputs
    new Tagify(input1);
    new Tagify(input2);

    document.addEventListener('input', function (e) {
        if (e.target && e.target.id === 'commission-input') {
            // Remove any non-numeric or non-decimal characters
            e.target.value = e.target.value.replace(/[^0-9.]/g, '');

            // Ensure there is only one decimal point
            if (e.target.value.split('.').length > 2) {
            e.target.value = e.target.value.slice(0, e.target.value.lastIndexOf('.'));
            }

            // Ensure the value is within the specified range
            const value = parseFloat(e.target.value);
            if (isNaN(value) || value < 0.00 || value > 1000.00) {
            e.target.setCustomValidity('Please add a value from 0.00 to 1000.00');
            } else {
            e.target.setCustomValidity('');
            }
        }
    });
</script>
{{-- {!! JsValidator::formRequest('Modules\CoreData\Http\Requests\Category\EditRequest','#kt_category_edit_form') !!} --}}
@endpush
