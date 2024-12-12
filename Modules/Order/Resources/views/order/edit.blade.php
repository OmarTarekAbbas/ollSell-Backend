@extends($layout)


@section('title', 'Edit order #' . $order->id)

@section('content')
<div id="kt_app_toolbar" class="app-toolbar  py-4 py-lg-8 ">

    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container  container-fluid d-flex flex-stack flex-wrap ">
        <!--begin::Toolbar wrapper-->
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">


            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                    Order #{{$order->id}}
                </h1>
                <!--end::Title-->

                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{url('/')}}" class="text-muted text-hover-primary">
                            Home
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        Orders
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-900">
                        Edit Order #{{$order->id}}
                    </li>
                    <!--end::Item-->

                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->

        </div>
        <!--end::Toolbar wrapper-->
    </div>
    <!--end::Toolbar container-->
</div>
<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::Form-->
                <form method="POST" action="{{ route('order.update', $order) }}" id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row">
                    @csrf
                    @method('patch')

                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">

                        <!--begin::Order details-->
                        <div class="card card-flush py-4">

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                @include('dashboard.error.error')
                                <!--begin::Billing address-->
                                <div class="d-flex flex-column gap-5 gap-md-7">
                                    <!--begin::Title-->
                                    <div class="fs-3 fw-bold mb-n2">Address Details</div>
                                    <!--end::Title-->
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">Street</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input class="form-control" name="customerAddress" value="{{ old('customerAddress') ?? $order->customerAddress}}" placeholder="Customer Address" required />
                                            <!--end::Input-->
                                        </div>
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="form-label">District</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input class="form-control" name="district" value="{{ old('district') ?? $order->district}}" placeholder="District" />
                                            <!--end::Input-->
                                        </div>

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="form-label">customer Location</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="url" class="form-control" name="customerLocation" value="{{ old('customerLocation') ?? $order->customerLocation}}" placeholder="CustomerLocation" />
                                            <!--end::Input-->
                                        </div>

                                    </div>
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">Country</label>
                                            <!--end::Label-->

                                            <!--begin::Select2-->
                                            <select id="country_id" class="form-select" name="country_id" data-control="select2" data-placeholder="Select an option" required>
                                                <option></option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}" {{ $order->country_id == $country->id ? 'selected' : '' }}>{{ $country->name->value }}</option>
                                                @endforeach
                                            </select>
                                            <!--end::Select2-->
                                        </div>
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">City</label>
                                            <!--end::Label-->
                                            <!--begin::Select2-->
                                            <select id="cities" class="form-select" name="customerCity" data-control="select2" data-placeholder="Select an option" required>
                                                <option></option>
                                            </select>
                                            <!--end::Select2-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <div class="fs-3 fw-bold mb-n2">Customer Details</div>

                                    <div class="d-flex flex-column flex-md-row gap-5">

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">Customer Name</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input class="form-control" name="customerName" placeholder="Customer Name" value="{{ old('customerName') ?? $order->customerName}}" required />
                                            <!--end::Input-->
                                        </div>

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">phone Code</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <select class="form-select" name="phone_code" required>
                                                <option value="">Select Country Code</option>
                                                @foreach ($codes as $code)
                                                    <option value="{{ $code['phoneCode'] }}" {{ $order->phone_code == $code['phoneCode'] ? 'selected' : '' }}>
                                                        {{ $code['country_name'] }} (+{{ $code['phoneCode'] }})
                                                    </option>
                                                @endforeach
                                            </select>

                                            <!--end::Input-->
                                        </div>

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">Phone</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input class="form-control" name="customerPhone" value="{{old('customerPhone') ?? $order->customerPhone}}" placeholder="Phone number" required />
                                            <!--end::Input-->
                                        </div>

                                    </div>
                                    <!--end::Input group-->


                                </div>
                                <!--end::Billing address-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Order details-->
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a href="{{route('order.show', $order)}}" class="btn btn-light me-5">Cancel</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <input type="submit" class="btn btn-primary" value="Save Changes" />
                            <!--end::Button-->
                        </div>
                    </div>
                    <!--end::Main column-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

</div>
<!--end:::Main-->

@endsection

@section('second-sidebar')
@include('order::layouts.sidebar')
@endsection
@push('scripts')
<script>
    var routeAll = "{{ route('order.create',Request()->all()) }}";
    var csrfToken = "{{ csrf_token() }}";
</script>

<script>
    $(document).ready(function() {
        var citiesAjaxRoute = "{{ route('ajax.cities') }}";
        var defaultCityId = "{{ $order->customerCity }}"; // Get the default city ID from the order object
        var isLoading = false; // Flag to track loading state

        function loadingCities() {
            isLoading = true;
            $('#cities').html('<option>Loading cities...</option>');
        }

        function stopLoading() {
            isLoading = false;
            $('#cities').html('<option>Loading cities...</option>');
        }

        $('#country_id').on('change', function() {
            var countryId = $(this).val();
            loadCities(defaultCityId, countryId);
        });

        function loadCities(defaultCityId, countryId = null) {
            loadingCities();
            $.ajax({
                url: citiesAjaxRoute,
                type: 'GET',
                data: {
                    'country_id': countryId
                },
                dataType: 'json',
                success: function(data) {
                    stopLoading();
                    $('#cities').html('<option value=""></option>');
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            var option = $('<option></option>')
                                .val(data[i].id)
                                .text(data[i].name.value);
                            $('#cities').append(option);
                        }
                    } else {
                        $('#cities').html('<option value="">No cities available</option>');
                    }
                    // Set the default selected city after cities are loaded
                    if (defaultCityId) {
                        $('#cities').val(defaultCityId);
                    }
                },
                error: function(request, error) {
                    stopLoading();

                    console.log(JSON.stringify(request));
                }
            });
        }

        // Load cities for the default country on page load
        loadCities(defaultCityId);

        // Check and show loading state if still loading after 1 second
        setInterval(function() {
            if (isLoading) {
                loadingCities();
            }
        }, 1000);
    });
</script>

@endpush
