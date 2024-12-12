@extends($layout)

@section('title', 'Edit Feature')

@section('content')

<!--begin::Basic info-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_category_create" aria-expanded="true" aria-controls="kt_category_create">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Feature Details</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Content-->
    <div id="kt_account_settings_profile_details" class="collapse show">
        <!--begin::Form-->
        @include('dashboard.error.error')
        <form id="kt_feature_create_form" class="form" method="post" action="{{route('feature.update',$data->id)}}">
            @csrf
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                @foreach(language() as $lang)
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Name {{$lang->code}}</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <input type="text" name="name[{{$lang->code}}]" value="{{$data->nameValue($lang)}}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name {{$lang->code}}" />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>

                @endforeach
                <!--end::Input group-->
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
    var route = "{{ route('feature.index') }}";
    // The DOM elements you wish to replace with Tagify
    var input1 = document.querySelector("#kt_tagify_1");
    var input2 = document.querySelector("#kt_tagify_2");

    // Initialize Tagify components on the above inputs
    new Tagify(input1);
    new Tagify(input2);
</script>
{{-- {!! JsValidator::formRequest('Modules\CoreData\Http\Requests\feature\EditRequest','#kt_feature_create_form') !!} --}}
@endpush

