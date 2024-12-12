@extends($layout)
@section('title', 'Category - Details')
@section('content')

<!--begin::Basic info-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_user_edit" aria-expanded="true" aria-controls="kt_user_edit">
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
        <!--begin::Card body-->
        <div class="card-body border-top p-9">
            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">ID</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <input type="text" disabled name="id" value="{{$data->id}}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="id" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

             <!--begin::Input group-->
             <div class="row mb-6">
                @foreach(language() as $lang)
                <div class="col">
                    <!--begin::Label-->
                    <label class="col-form-label fw-semibold fs-6">Name ({{$lang->name}})</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div>
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="fv-row">
                                <input type="text" disabled name="id" disabled value="{{$data->nameValue($lang)}}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="ID" />
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

            <!--begin::Input group-->
            <div class="row mb-6">
                @foreach(language() as $lang)
                <div class="col">
                    <!--begin::Label-->
                    <label class="col-form-label fw-semibold fs-6">Meta Title ({{$lang->name}})</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div>
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="fv-row">
                                <input type="text" disabled name="id" disabled value="{{$data->metaTitleValue($lang)}}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" />
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

             <!--begin::Input group-->
             <div class="row mb-6">
                @foreach(language() as $lang)
                <div class="col">
                    <!--begin::Label-->
                    <label class="col-form-label fw-semibold fs-6">Meta Description ({{$lang->name}})</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div>
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="fv-row">
                                <textarea rows="3" type="text" disabled name="id" value="{{$data->metaDescription->value}}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0">{{$data->metaDescriptionValue($lang)}}</textarea>
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

             <!--begin::Input group-->
             <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Category Parent</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <input type="text" disabled name="metaDescription" value="@if($data->parent_id) {{$data->parent->name->value}} @else --- @endif" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="ID" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

              <!--begin::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Commission</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <input type="text" disabled name="metaDescription" value="  @if($data->commission) {{$data->commission}}% @else --- @endif" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="ID" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

        </div>
        <!--end::Input group-->
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{  route('category.index') }}" class="btn btn-light btn-active-light-primary me-2">Discard</a>
            @permission('update_categories')
            <a href="{{  route('category.edit',$data->id) }}" class="btn btn-light btn-active-light-primary me-2">Edit</a>
            @endpermission
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Content-->
</div>
<!--end::Basic info-->

@endsection

@section('second-sidebar')
@include('mastercatalog::layouts.sidebar')
@endsection


@push('scripts')
@endpush
