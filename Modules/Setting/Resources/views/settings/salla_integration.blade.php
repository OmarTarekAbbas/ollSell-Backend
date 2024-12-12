@extends($layout)


@section('title', 'Setting')

@section('content')

<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_user_create" aria-expanded="true"
        aria-controls="kt_user_create">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">SALLA Integration</h3>
        </div>
    </div>
    <div id="kt_account_settings_profile_details" class="collapse show">
        @include('dashboard.error.error')
        <form id="kt_setting_edit_form" class="form" method="post" action="{{route('setting.salla_integration')}}"
            enctype="multipart/form-data">
            @csrf


            <div class="card-body  p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">SALLA BASE URL</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="url" name="SALLA_BASE_URL" value="{{setting('SALLA_BASE_URL')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="SALLA BASE URL" />

                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">SALLA OAUTH CLIENT ID</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="SALLA_OAUTH_CLIENT_ID" value="{{setting('SALLA_OAUTH_CLIENT_ID')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="SALLA OAUTH CLIENT ID" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">SALLA OAUTH CLIENT SECRET</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="SALLA_OAUTH_CLIENT_SECRET" value="{{setting('SALLA_OAUTH_CLIENT_SECRET')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="SALLA OAUTH CLIENT SECRET" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">SALLA OAUTH CLIENT REDIRECT URI</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="url" name="SALLA_OAUTH_CLIENT_REDIRECT_URI" value="{{setting('SALLA_OAUTH_CLIENT_REDIRECT_URI')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="SALLA OAUTH CLIENT REDIRECT URI" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">SALLA WEBHOOK SECRET</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="SALLA_WEBHOOK_SECRET" value="{{setting('SALLA_WEBHOOK_SECRET')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="SALLA WEBHOOK SECRET" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">SALLA AUTHORIZATION MODE</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="SALLA_AUTHORIZATION_MODE" value="{{setting('SALLA_AUTHORIZATION_MODE')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="SALLA AUTHORIZATION MODE" />
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Easy Order</h3>
            </div>

            <div class="card-body p-9">
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Easy WEBHOOK CLIENT SECRET</label>
                    <div class="col-lg-9 d-flex align-items-center">
                        <div class="form-check form-check-solid form-switch fv-row">
                            <input type="text" name="WEBHOOK_CLIENT_SECRET" value="{{setting('WEBHOOK_CLIENT_SECRET')}}"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="Easy WEBHOOK CLIENT SECRET" />
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