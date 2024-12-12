@extends($layout)



@section('title', 'Create feature')

@section('content')

<!--begin::Basic info-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_category_create" aria-expanded="true" aria-controls="kt_category_create">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">New feature</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Content-->
    <div id="kt_account_settings_profile_details" class="collapse show">
        <!--begin::Form-->
        @include('dashboard.error.error')

        <form id="kt_category_create_form" class="form" method="post" action="{{route('feature.store')}}">
            @csrf
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <div class="row mb-6">
                    @foreach(language() as $lang)
                    <div class="col-lg-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Name {{$lang->code == 'ar' ? 'Arabic' : 'English'}}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-12">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-12 fv-row">
                                    <input type="text" name="name[{{$lang->code}}]" value="{{Request::old('name['.$lang->code.']')}}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name {{ $lang->code}}" />
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
            <!--end::Input group-->

            <!--end::Card body-->
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{  route('feature.index') }}" class="btn btn-light btn-active-light-primary me-2">Discard</a>
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
@include('subscription::layouts.sidebar')
@endsection

@push('scripts')
<script src="{{ asset('dashboard2')  }}/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<script src="{{ asset('dashboard2')  }}/assets/js/custom/apps/ecommerce/catalog/save-product.js"></script>
<script src="{{ asset('dashboard2')  }}/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>
<script>
    var route = "{{ route('category.index') }}";

    // The DOM elements you wish to replace with Tagify
    var input1 = document.querySelector("#kt_tagify_1");
    var input2 = document.querySelector("#kt_tagify_2");

    // Initialize Tagify components on the above inputs
    new Tagify(input1);
    new Tagify(input2);
</script>

{{-- {!! JsValidator::formRequest('Modules\CoreData\Http\Requests\Category\CreateRequest','#kt_category_create_form') !!} --}}


@foreach(language() as $lang)
<script>
    ClassicEditor
    .create(document.querySelector('#kt_docs_ckeditor_classic_{{ $lang->code }}'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
</script>
@endforeach

@endpush

