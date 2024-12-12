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
                <h3 class="fw-bold m-0">User</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            @include('dashboard.error.error')
            <form id="kt_user_edit_form" class="form" method="post" action="{{route('user.update',$data->id)}}"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="suspended" value="0">
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    @if(user()->can('update_users'))
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Role</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <select name="role_id" id="role" aria-label="Select a Role" data-control="select2"
                                                data-placeholder="Select a Role..."
                                                class="form-select form-select-solid form-select-lg fw-semibold">
                                            <option value="">Select a Role...</option>
                                            @foreach($role as $value)
                                                <option value="{{$value->id}}"
                                                        data-type="{{$value->type}}"
                                                        @if(in_array($value->id,$data->roles->pluck('id')->toArray())) selected @endif
                                                >
                                                    {{$value->name}}
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
                        <!--end::Input group-->
                    @else
                        <input type="hidden" name="role_id" value="{{$data->roles->pluck('id')->toArray()[0]}}">
                    @endif
                    <input
                        type="hidden" name="type" value="1"
                        @if($data->roles->pluck('type')->toArray()[0] != 1)
                            disabled
                        @endif
                    >
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
                                    <input type="text" name="name" value="{{$data->name}}"
                                           class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                           placeholder="Name"/>
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
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Email</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="email" name="email" value="{{$data->email}}" @if(user()->role->role->id != 1) disabled @endif
                                           class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                           placeholder="Email"/>
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
                    <a href="@if(user()->can('view_users')){{ route('user.index') }} @else {{ route('dashboard') }}@endif" class="btn btn-light btn-active-light-primary me-2">Discard</a>
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
        let suspended = {{user()->suspended}};
        if(suspended){
            alert("Please add the missing data to be able to access the dashboard.");
        }
        let route = "{{ route('user.index') }}";
        let type = {{$data->roles->pluck('type')->toArray()[0] ?? 0}};

    </script>
@endpush
