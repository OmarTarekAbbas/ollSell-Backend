@extends($layout)
@section('title', 'Warehouses - Edit')
@section('content')
<!--begin::Basic info-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_user_edit" aria-expanded="true" aria-controls="kt_user_edit">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Warehouse</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Content-->
    <div id="kt_account_settings_profile_details" class="collapse show">
        <!--begin::Form-->
        @include('dashboard.error.error')
        <form id="kt_user_edit_form" class="form" method="post" action="{{route('supplier.warehouse.update',$data->id)}}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <input type="hidden" name="suspended" value="0">
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
                                <input type="text" name="name" value="{{ old('name') ?: $data->name }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" />
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
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Country</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <select class="form-select" data-control="select2" data-placeholder="Select an option" name="country_id" id="country_id">
                                    <option></option>
                                    @foreach ($countries as $country)
                                    <option {{ old('country_id') == $country->id ? 'selected' : ($data->country_id ==  $country->id ? 'selected' : '') }} value="{{ $country->id }}">{{ $country->name->value }}</option>
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
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">City</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row" id="citySelect">
                                <select class="form-select" data-control="select2" data-placeholder="Select an option" name="city_id" id="cities">
                                    <option value="{{ $data->city_id }}">{{ $data->city->name->value }}</option>
                                    @foreach ($cities as $city)
                                    <option {{ $data->city_id ==  $city->id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name->value }}</option>
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

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Address</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <input id="address" type="text" name="address" value="{{ old('address') ?: $data->address }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Address" />
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
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">District</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <input id="district" type="text" name="district" value="{{ old('district') ?: $data->district }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="District Name" />
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
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Location</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <input id="location" type="text" name="location" value="{{ old('location') ?: $data->location }}" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="https://goo.gl/maps/..........." />
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
                <a href="{{ route('supplier.warehouse.index') }}" class="btn btn-light btn-active-light-primary me-2">Discard</a>
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
@include('supplier::layouts.sidebar')
@endsection


@push('scripts')
<script>
    var citiesAjaxRoute = "{{ route('ajax.cities') }}"
    $('#country_id').on('change', function(){
        var countryId = $(this).val();
        $.ajax({
            url : citiesAjaxRoute,
            type : 'GET',
            data : {
                'country_id' : countryId
            },
            dataType:'json',
            success : function(data) {
                $('#cities').html(null);
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement('option');
                    console.log(data[i].name.value);
                    option.value = data[i].id;
                    option.text = data[i].name.value;
                    $('#cities').append(option);
                }
            },
            error : function(request,error)
            {
                console.log(JSON.stringify(request));
            }
        });
    });

 </script>

<script>
    document.getElementById("district").addEventListener("input", function (e) {
        const inputValue = e.target.value;
        const validValue = inputValue.replace(/[^A-Za-z ]/g, "");
        if (inputValue !== validValue) {
            e.target.value = validValue;
        }
    });
</script>

@endpush
