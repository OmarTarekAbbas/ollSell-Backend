@extends($layout)


@section('title', 'state')

@section('content')

    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
             data-bs-target="#kt_state_create" aria-expanded="true"
             aria-controls="kt_state_create">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">state</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            @include('dashboard.error.error')
            <form id="kt_state_create_form" class="form" method="post" action="{{route('state.store')}}"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="1" disabled>
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    @foreach(language() as $lang)
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label
                                class="col-lg-4 col-form-label required fw-semibold fs-6">Name {{$lang->code}}</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <input type="text" name="name[{{$lang->code}}]"
                                               value="{{Request::old('name['.$lang->code.']')}}"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                               placeholder="Name {{$lang->code}}"/>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                    @endforeach
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">country</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <select name="country_id" id="country" aria-label="Select a country" data-control="select2"
                                            data-placeholder="Select a country..."
                                            value="{{old('country_id')}}"
                                            class="form-select form-select-solid form-select-lg fw-semibold">
                                        <option value="">Select a country...</option>
                                        @foreach($countries as $value)
                                            <option
                                                value="{{$value->id}}"
                                            >
                                                {{$value->name->value}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">city</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <select name="city_id" id="city" aria-label="Select a country" data-control="select2"
                                            data-placeholder="Select a city..."
                                            class="form-select form-select-solid form-select-lg fw-semibold"
                                            value="{{old('city_id')}}"    
                                        >
                                        <option value="">Select a city...</option>
                                        @foreach($cities as $value)
                                            <option
                                                value="{{$value->id}}"
                                            >
                                                {{$value->name->value}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                </div>
                <!--end::Input group-->
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{  route('state.index') }}" class="btn btn-light btn-active-light-primary me-2">Discard</a>
                    <button type="submit" class="btn btn-primary" id="kt_state_create_submit">Save Changes
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
@include('coredata::layouts.sidebar')
@endsection
