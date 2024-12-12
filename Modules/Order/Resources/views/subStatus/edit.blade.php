@extends($layout)


@section('title', 'Status')

@section('content')

<!--begin::Basic info-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" TargetMarket="button" data-bs-toggle="collapse" data-bs-target="#kt_product_edit" aria-expanded="true" aria-controls="kt_product_edit">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Edit {{ $subStatus->name }} </h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Content-->
    <div id="kt_account_settings_profile_details" class="collapse show">
        <!--begin::Form-->
        @include('dashboard.error.error')
        <form id="kt_product_edit_form" class="form" method="post" action="{{ route('order.subStatus.update', $subStatus->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <div class="d-flex flex-column mb-10 fv-row">
                    <!--begin::Label-->
                    <div class="fs-5 fw-bold form-label mb-3 required">
                        Substatus name
                    </div>
                    <!--end::Label-->
                    <input type="text" name="name" value="{{ old('name', $subStatus->name) }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Sub status name" required />
                </div>
                <div class="d-flex flex-column mb-10 fv-row">
                    <!--begin::Label-->
                    <div class="fs-5 fw-bold form-label mb-3 required">
                        Main status
                    </div>
                    <!--end::Label-->
                    <select class="form-select" name="status_id">
                        <option value="">Select parent status</option>
                        @foreach($statuses as $record)
                            <option value="{{ $record['id'] }}" {{ old('status_id', $subStatus->status_id) == $record['id'] ? 'selected' : '' }}>{{ $record['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-column mb-10 fv-row">
                    <!--begin::Label-->
                    <div class="fs-5 fw-bold form-label mb-3">
                        Remarks
                    </div>
                    <!--end::Label-->

                    <input name="remarks" value='{{ old('remarks', implode(',', $subStatus->remarks->pluck('name')->toArray())) }}' class="form-control">
                </div>
            </div>
            <!--end::Card body-->
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('order.subStatus.index') }}" class="btn btn-light btn-active-light-primary me-2">Discard</a>
                <button type="submit" class="btn btn-primary" id="kt_product_edit_submit">
                    Save
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/@yaireo/tagify"></script>
    <script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // The DOM element you wish to replace with Tagify
            var input = document.querySelector('input[name="remarks"]');

            // Initialize Tagify on the above input node reference
            var tagify = new Tagify(input);

        });
    </script>
@endpush

@section('second-sidebar')
@include('order::layouts.sidebar')
@endsection
