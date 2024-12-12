@extends($layout)


@section('title', 'Attribute')

@section('content')

<!--begin::Basic info-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_attribute_create" aria-expanded="true" aria-controls="kt_attribute_create">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Attribute Details</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Content-->
    <div id="kt_account_settings_profile_details" class="collapse show">
        <!--begin::Form-->
        @include('dashboard.error.error')
        <form id="kt_attribute_edit_form" class="form" method="post" action="{{route('attribute.update',$data->id)}}">
            @csrf
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Name</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <input type="text" name="name" value="{{ old('name', $data->name) }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>

                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Options</label>
                    <!--begin::Repeater-->
                    <div id="kt_docs_repeater_basic">
                        <!--begin::Form group-->
                        <div class="form-group">
                            <div data-repeater-list="options">
                                @foreach ($data->options as $option)
                                    <div data-repeater-item>
                                        <div class="form-group row mb-5">
                                            <div class="col-md-6">
                                                <label class="form-label">Name:</label>
                                                <input type="text" name="name" class="form-control mb-2 mb-md-0" value="{{ $option->name }}" placeholder="Enter name" />
                                            </div>
                                            <div class="col-md-6">
                                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!--end::Form group-->

                        <!--begin::Form group-->
                        <div class="form-group mt-5">
                            <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                Add
                            </a>
                        </div>
                        <!--end::Form group-->
                    </div>
                    <!--end::Repeater-->
                    <!--end::Col-->
                </div>
            </div>
            <!--end::Input group-->
            <!--end::Card body-->
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{  route('attribute.index') }}" class="btn btn-light btn-active-light-primary me-2">Discard</a>
                <button type="submit" class="btn btn-primary" id="kt_attribute_create_submit">Save Changes
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
    var route = "{{ route('attribute.index') }}";
     // The DOM elements you wish to replace with Tagify
     var input1 = document.querySelector("#kt_tagify_1");
    var input2 = document.querySelector("#kt_tagify_2");

    // Initialize Tagify components on the above inputs
    new Tagify(input1);
    new Tagify(input2);
</script>
{{-- {!! JsValidator::formRequest('Modules\CoreData\Http\Requests\Attribute\EditRequest','#kt_attribute_edit_form') !!} --}}
<script src="{{ asset('dashboard2')  }}/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>

<script>
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
</script>
@endpush