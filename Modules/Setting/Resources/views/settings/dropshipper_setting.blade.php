@extends($layout)


@section('title', 'Setting')

@section('content')

    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
             data-bs-target="#kt_user_create" aria-expanded="true"
             aria-controls="kt_user_create">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Dropshipper Setting</h3>
            </div>
        </div>
        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('dashboard.error.error')
            <form id="kt_setting_edit_form" class="form" method="post" action="{{route('setting.dropshipper_setting')}}"
                  enctype="multipart/form-data">
                @csrf
      
                <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Enable Salla Integration</label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input class="form-check-input w-45px h-30px" type="checkbox" name="enable_salla_integration" id="allowmarketing"{{old('enable_salla_integration',setting('enable_salla_integration'))?'checked':''}}>
                                <label class="form-check-label" for="allowmarketing"></label>
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
