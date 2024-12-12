@extends($layout)


@section('title', 'Setting')

@section('content')
<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_user_create" aria-expanded="true"
        aria-controls="kt_user_create">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Pay Integration</h3>
        </div>
    </div>
    <div id="kt_account_settings_profile_details" class="collapse show">
        @include('dashboard.error.error')
        <form id="kt_setting_edit_form" class="form" method="post" action="{{route('setting.pay_setting')}}"
            enctype="multipart/form-data">
            @csrf


            <div class="card-body  p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">CLICKPAY URL</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="url" name="CLICKPAY_ENDPOINT" value="{{setting('CLICKPAY_ENDPOINT')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="CLICKPAY URL" />

                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">CLICKPAY MERCHANT ID</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="CLICKPAY_MERCHANT_ID" value="{{setting('CLICKPAY_MERCHANT_ID')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="AYMAKAN AP KEY" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">CLICKPAY SERVER KEY</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="CLICKPAY_SERVER_KEY" value="{{setting('CLICKPAY_SERVER_KEY')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="CLICKPAY SERVER KEY" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">CLICKPAY CLIENT KEY</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="CLICKPAY_CLIENT_KEY" value="{{setting('CLICKPAY_CLIENT_KEY')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="CLICKPAY CLIENT KEY" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">CLICKPAY PAYMENTS CURRENCY</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="CLICKPAY_PAYMENTS_CURRENCY" value="{{setting('CLICKPAY_PAYMENTS_CURRENCY')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="CLICKPAY PAYMENTS CURRENCY" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">CLICKPAY PAYMENTS MODE</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="CLICKPAY_PAYMENTS_MODE" value="{{setting('CLICKPAY_PAYMENTS_MODE')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="CLICKPAY PAYMENTS MODE" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{  route('dashboard') }}" class="btn btn-light btn-active-light-primary me-2">Discard</a>
                <button type="submit" class="btn btn-primary" id="kt_user_create_submit">Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('second-sidebar')
@include('setting::layouts.sidebar')
@endsection