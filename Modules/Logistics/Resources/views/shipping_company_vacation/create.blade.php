@extends($layout)


@section('title', 'Shipping Company vacation')

@section('content')

    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_shipping_company_vacation_create" aria-expanded="true"
            aria-controls="kt_shipping_company_vacation_create">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Shipping Company Vacation</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            @include('dashboard.error.error')
            <form id="kt_shipping_company_vacation_create_form" class="form" method="post"
                action="{{ route('shipping_company_vacation.store') }}">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top p-9">

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Shipping Company</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <select name="shipping_company_id" id="shipping_company_id"
                                        aria-label="Select a Shipping Company" data-control="select2"
                                        data-placeholder="Select a Shipping Company..."
                                        class="form-select form-select-solid form-select-lg fw-semibold">
                                        <option value="">Select a Shipping Company...</option>
                                        @foreach ($shippingcompanies as $value)
                                            <option value="{{ $value->id }}">
                                                {{ $value->name }}
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
                    <!--begin::Input group-->
             
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Title </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="title" value="{{ Request::old('title') }}"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="Title" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                    <div id="date_range" class="mb-8 d-flex justify-content-between required">
                        <div class="mx-2">
                            <label class="form-label fs-6 fw-semibold required">From Date:</label>
                            <input
                                type="text"
                                name="start_day"
                                id="fromDate"
                                autocomplete="off"
                                value=""

                                style="margin-right:5px"
                                class="form-select"
                            />
                        </div>
                        <div>
                            <label class="form-label fs-6 fw-semibold required">End Date:</label>

                            <input
                                type="text"
                                name="end_day"
                                id="toDate"
                                autocomplete="off"
                                value=""
                                style="margin-right:5px"
                                class="form-select"
                            />
                        </div>
                    </div>
                </div>

                </div>
                <!--end::Input group-->
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('shipping_company_vacation.index') }}" class="btn btn-light btn-active-light-primary me-2">Discard</a>
                    <button type="submit" class="btn btn-primary" id="kt_city_create_submit">Save Changes
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
    <script>



$(function() {

$('input[name="start_day"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('Y-MM-DD'));
});
$('input[name="end_day"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('Y-MM-DD'));
});

$('input[name="start_day"]').daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    // defaultValue: null,
    locale: {
        format: 'Y-MM-DD',
    }
});

$('input[name="start_day"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

$('input[name="end_day"]').daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    locale: {
        format: 'Y-MM-DD',
    }
});

$('input[name="end_day"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

});
    </script>
@endpush
@section('second-sidebar')
    @include('logistics::layouts.sidebar')
@endsection
