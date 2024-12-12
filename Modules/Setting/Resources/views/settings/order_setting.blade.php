@extends($layout)


@section('title', 'Setting')

@section('content')

<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_user_create" aria-expanded="true"
        aria-controls="kt_user_create">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Order setting</h3>
        </div>
    </div>
    <div id="kt_account_settings_profile_details" class="collapse show">
        @include('dashboard.error.error')
        <form id="kt_setting_edit_form" class="form" method="post" action="{{route('setting.order_setting')}}"
            enctype="multipart/form-data">
            @csrf

            {{-- <div class="card-body border-top p-9">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Stock Quantity</label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch fv-row">
                                <input class="form-check-input w-45px h-30px" type="checkbox" name="enable_stock_quantity" id="allowmarketing"{{old('enable_stock_quantity',setting('enable_stock_quantity'))?'checked':''}}>
            <label class="form-check-label" for="allowmarketing"></label>
    </div>
</div>
</div>
</div> --}}
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label  fw-semibold fs-6">Fake Number</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8">
        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div class="col-lg-6 fv-row">
                <input type="text" name="fake_number" value="{{ setting('fake_number') }}"
                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                    placeholder="Fake Number" />
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Col-->
</div>

<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label  fw-semibold fs-6">Vat Product</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8">
        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div class="col-lg-6 fv-row">
                <input type="text" name="vat_product" value="{{ setting('vat_product') }}"
                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                    placeholder="Vat Product" />
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Col-->
</div>

<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label  fw-semibold fs-6">Vat Profit</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8">
        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div class="col-lg-6 fv-row">
                <input type="text" name="vat_profit" value="{{ setting('vat_profit') }}"
                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                    placeholder="Vat Profit" />
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Col-->
</div>

<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label  fw-semibold fs-6">Vat Suppler</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8">
        <!--begin::Row-->
        <div class="row">
            <!--begin::Col-->
            <div class="col-lg-6 fv-row">
                <input type="text" name="vat_suppler" value="{{ setting('vat_suppler') }}"
                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                    placeholder="Vat Suppler" />
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Col-->
</div>


<hr>

<div class="card-title m-0">
    <h3 class="fw-bold m-0">Pay Method</h3>
</div>

<div class="card-body p-9">
    <div class="row mb-6">
        <label class="col-lg-3 col-form-label fw-semibold fs-6">CASH ON DELIVERY</label>
        <div class="col-lg-9 d-flex align-items-center">
            <div class="col-lg-8">
                <!--begin::Row-->
                <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-6 fv-row">
                        <select class="form-control form-select" name="CASH_ON_DELIVERY">
                            <option value="1" {{ setting('CASH_ON_DELIVERY') == '1' ? 'selected' : '' }}>True</option>
                            <option value="0" {{ setting('CASH_ON_DELIVERY') == '0' ? 'selected' : '' }}>False</option>
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
        </div>
    </div>
</div>

<div class="card-body p-9">
    <div class="row mb-6">
        <label class="col-lg-3 col-form-label fw-semibold fs-6">ONLINE METHOD</label>
        <div class="col-lg-9 d-flex align-items-center">
            <div class="col-lg-8">
                <!--begin::Row-->
                <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-6 fv-row">
                        <select class="form-control form-select" name="ONLINE_METHOD">
                            <option value="1" {{ setting('ONLINE_METHOD') == '1' ? 'selected' : '' }}>True</option>
                            <option value="0" {{ setting('ONLINE_METHOD') == '0' ? 'selected' : '' }}>False</option>
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
        </div>
    </div>
</div>

<div class="card-body p-9">
    <div class="row mb-6">
        <label class="col-lg-3 col-form-label fw-semibold fs-6">WALLET METHOD</label>
        <div class="col-lg-9 d-flex align-items-center">
            <div class="col-lg-8">
                <!--begin::Row-->
                <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-6 fv-row">
                        <select class="form-control form-select" name="WALLET_METHOD">
                            <option value="1" {{ setting('WALLET_METHOD') == '1' ? 'selected' : '' }}>True</option>
                            <option value="0" {{ setting('WALLET_METHOD') == '0' ? 'selected' : '' }}>False</option>
                        </select>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
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