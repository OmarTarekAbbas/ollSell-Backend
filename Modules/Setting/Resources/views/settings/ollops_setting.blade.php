@extends($layout)


@section('title', 'Setting')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
             data-bs-target="#kt_user_create" aria-expanded="true"
             aria-controls="kt_user_create">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Ollops Integration</h3>
            </div>
        </div>
        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.error.error')
            <form id="kt_setting_edit_form" class="form" method="post" action="{{route('setting.ollops_setting')}}"
                  enctype="multipart/form-data">
                @csrf
      

                <div class="card-body  p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">OLLOPS BASE URL</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="url" name="OLLOPS_BASE_URL" value="{{setting('OLLOPS_BASE_URL')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="OLLOPS BASE URL"/>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">OLLOPS APP ID</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="OLLOPS_APP_ID" value="{{setting('OLLOPS_APP_ID')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="OLLOPS APP ID"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">VITE OLLOPS BASE FRONT URL</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="url" name="VITE_OLLOPS_BASE_FRONT_URL" value="{{setting('VITE_OLLOPS_BASE_FRONT_URL')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="VITE OLLOPS BASE FRONT URL"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">OLLOPS ENV</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="OLLOPS_ENV" value="{{setting('OLLOPS_ENV')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="OLLOPS ENV"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">OLLOPS API KEY DEV</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="OLLOPS_API_KEY_DEV" value="{{setting('OLLOPS_API_KEY_DEV')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="OLLOPS API KEY DEV"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-semibold fs-6">OLLOPS API KEY LIVE</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input type="text" name="OLLOPS_API_KEY_LIVE" value="{{setting('OLLOPS_API_KEY_LIVE')}}"
                                       class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                       placeholder="OLLOPS API KEY LIVE"/>
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
