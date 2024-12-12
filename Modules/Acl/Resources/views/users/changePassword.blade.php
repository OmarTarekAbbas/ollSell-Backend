@extends($layout)


@section('title', 'User')

@section('content')

    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
             data-bs-target="#kt_user_edit" aria-expanded="true"
             aria-controls="kt_user_edit">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Change Password</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            @include('dashboard.error.error')
            <form id="kt_user_edit_form" class="form" method="post" action="{{route('user.updatePassword',$data->id)}}"
                  enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Password</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="password" name="password" value="{{Request::old('password')}}"
                                           class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                           placeholder="Password"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Password Confirmation</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="password" name="password_confirmation"
                                           value="{{Request::old('password_confirmation')}}"
                                           class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                           placeholder="Password Confirmation"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Input group-->
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    @if(user()->id == $data->id)
                        <a href="{{  route('user.show',$data->id) }}"
                           class="btn btn-light btn-active-light-primary me-2">Discard</a>
                    @else
                        <a href="{{  route('user.index') }}"
                           class="btn btn-light btn-active-light-primary me-2">Discard</a>
                    @endif
                    <button type="submit" class="btn btn-primary" id="kt_user_edit_submit">Save Changes
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
@include('acl::layouts.sidebar')
@endsection


@push('scripts')

    <script>
        var route = "{{ route('user.index') }}";
        changeFieldRole({{$data->roles->pluck('id')->toArray()[0] ?? 0}});
        $('#role').change(function () {
            role = $(this).val();
            changeFieldRole(role);
        });

        function changeFieldRole(role) {
            if (role == 4) {
                document.getElementById('company_size').hidden = false;
                document.getElementById('website').hidden = false;
                document.getElementById('conatact_person_email').hidden = false;
                document.getElementById('conatact_person_name').hidden = false;
                document.getElementById('category').hidden = false;
            } else {
                document.getElementById('company_size').hidden = true;
                document.getElementById('website').hidden = true;
                document.getElementById('conatact_person_email').hidden = true;
                document.getElementById('conatact_person_name').hidden = true;
                document.getElementById('category').hidden = true;
            }
        }
    </script>
@endpush
