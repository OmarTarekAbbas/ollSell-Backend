@extends($layout)
@section('title', 'Onboarding Category')
@section('content')
    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
             data-bs-target="#kt_onboarding_category_create" aria-expanded="true"
             aria-controls="kt_onboarding_category_create">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Onboarding Category</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            @include('dashboard.error.error')
            <form id="kt_onboarding_category_create_form" class="form" method="post"
                  action="{{route('onboarding_category.store')}}" enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
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

                    </div>
                </div>
                <!--end::Input group-->
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{  route('onboarding_category.index') }}"
                       class="btn btn-light btn-active-light-primary me-2">Discard</a>
                    <button type="submit" class="btn btn-primary" id="kt_onboarding_category_create_submit">Save Changes
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
        var route = "{{ route('onboarding_category.index') }}";
    </script>
@endpush
