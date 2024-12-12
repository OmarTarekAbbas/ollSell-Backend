@extends($layout)


@section('title', 'Setting')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
             data-bs-target="#kt_user_create" aria-expanded="true"
             aria-controls="kt_user_create">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Email Setting</h3>
            </div>
        </div>
        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.error.error')
            <form id="kt_setting_edit_form" class="form" method="post" action="{{route('setting.email_setting')}}"
                  enctype="multipart/form-data">
                @csrf
      

                <div class="card-body  p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">MAIL MAILER</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="MAIL_MAILER" value="{{setting('MAIL_MAILER')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="MAIL MAILER"/>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">MAIL HOST</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="MAIL_HOST" value="{{setting('MAIL_HOST')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="MAIL HOST"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">MAIL PORT</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="MAIL_PORT" value="{{setting('MAIL_PORT')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="MAIL PORT"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">MAIL USERNAME</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="MAIL_USERNAME" value="{{setting('MAIL_USERNAME')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="MAIL USERNAME"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">MAIL PASSWORD</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="MAIL_PASSWORD" value="{{setting('MAIL_PASSWORD')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="MAIL PASSWORD"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">MAIL ENCRYPTION</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="MAIL_ENCRYPTION" value="{{setting('MAIL_ENCRYPTION')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="MAIL ENCRYPTION"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">MAIL FROM ADDRESS</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="MAIL_FROM_ADDRESS" value="{{setting('MAIL_FROM_ADDRESS')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="MAIL FROM ADDRESS"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">MAIL FROM NAME</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="MAIL_FROM_NAME" value="{{setting('MAIL_FROM_NAME')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="MAIL FROM NAME"/>
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
