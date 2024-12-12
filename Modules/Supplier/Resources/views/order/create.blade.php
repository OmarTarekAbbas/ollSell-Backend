@extends($layout)


@section('title', 'Order Details')

@section('content')
<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::Form-->
                <form id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row" method="POST" action="{{ route('order.store') }}">
                    @csrf
                    <!--begin::Aside column-->
                    <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
                        <!--begin::Order details-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Order Details</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-10">
                                    <!--begin::Input group-->
                                    <div class="fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Dropshipper</label>
                                        <!--end::Label-->
                                        <!--begin::Select2-->
                                        <select class="form-select" name="dropshipperId" data-control="select2" data-placeholder="Select an option">
                                            <option></option>
                                            @foreach ($dropshippers as $dropshipper)
                                            <option value="{{ $dropshipper->id }}">{{ $dropshipper->first_name . ' ' . $dropshipper->second_name }}</option>
                                            @endforeach

                                        </select>
                                        <!--end::Select2-->
                                        <!--begin::Description-->
                                        <div class="text-muted fs-7">Set the date of the order to process.</div>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="fv-row">
                                        <!--begin::Label-->
                                        <label class="required form-label">Payment Method</label>
                                        <!--end::Label-->
                                        <!--begin::Select2-->
                                        <select name="paymentMethod" class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" name="payment_method" id="kt_ecommerce_edit_order_payment">
                                            <option></option>
                                            <option value="1">Online</option>
                                            <option value="2">Cash on Delivery</option>
                                            <option value="3">Walet</option>
                                        </select>
                                        <!--end::Select2-->
                                        <!--begin::Description-->
                                        <div class="text-muted fs-7">Set the date of the order to process.</div>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Input group-->


                                </div>
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Order details-->
                    </div>
                    <!--end::Aside column-->
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
                        <!--begin::Order details-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Select Products</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-10">
                                    <!--begin::Input group-->
                                    <div>
                                        <!--begin::Label-->
                                        <label class="form-label">Add products to this order</label>
                                        <!--end::Label-->
                                        <!--begin::Selected products-->
                                        <div class="row row-cols-1 row-cols-xl-3 row-cols-md-2 border border-dashed rounded pt-3 pb-1 px-2 mb-5 mh-300px overflow-scroll" id="kt_ecommerce_edit_order_selected_products">
                                            <!--begin::Empty message-->
                                            <span class="w-100 text-muted">Select one or more products from the list below by ticking the checkbox.</span>
                                            <!--end::Empty message-->
                                        </div>
                                        <!--begin::Selected products-->
                                        <!--begin::Total price-->
                                        <div class="fw-bold fs-4">Total Cost: $
                                            <span id="kt_ecommerce_edit_order_total_price">0.00</span>
                                        </div>
                                        <!--end::Total price-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Separator-->
                                    <div class="separator"></div>
                                    <!--end::Separator-->
                                    <!--begin::Search products-->
                                    <div class="d-flex align-items-center position-relative mb-n7">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                        <input id="search" type="text" data-kt-ecommerce-edit-order-filter="search" class="form-control form-control-solid w-100 w-lg-50 ps-12" placeholder="Search Products" />
                                    </div>
                                    <!--end::Search products-->
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_edit_order_product_table">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="w-25px pe-2"></th>
                                                <th class="min-w-200px">Product</th>
                                                <th class="min-w-200px">Quantity</th>
                                                <th class="min-w-100px text-end pe-5">Qty Remaining</th>
                                            </tr>
                                        </thead>
                                        <tbody id="main_table" class="fw-semibold text-gray-600">
                                        </tbody>

                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Order details-->
                        <!--begin::Order details-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Delivery Details</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Billing address-->
                                <div class="d-flex flex-column gap-5 gap-md-7">
                                    <!--begin::Title-->
                                    <div class="fs-3 fw-bold mb-n2">Billing Address</div>
                                    <!--end::Title-->
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">Address Line 1</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input class="form-control" name="customerAddress" placeholder="Address Line 1" value="" />
                                            <!--end::Input-->
                                        </div>

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">customer Location</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="url" class="form-control" name="customerLocation" placeholder="CustomerLocation" value="" />
                                            <!--end::Input-->
                                        </div>

                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">phone Code</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input class="form-control" name="phone_code" placeholder="phone Code" value="" />
                                            <!--end::Input-->
                                        </div>

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">Phone</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input class="form-control" name="customerPhone" placeholder="Phone" value="" />
                                            <!--end::Input-->
                                        </div>

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">Customer Name</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input class="form-control" name="customerName" placeholder="Customer Name" value="" />
                                            <!--end::Input-->
                                        </div>


                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">Country</label>
                                            <!--end::Label-->

                                            <!--begin::Select2-->
                                            <select id="country_id" class="form-select" name="customerCountry" data-control="select2" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name->value }}</option>
                                                @endforeach
                                            </select>
                                            <!--end::Select2-->
                                        </div>
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required form-label">City</label>
                                            <!--end::Label-->
                                            <!--begin::Select2-->
                                            <select id="cities" class="form-select" name="customerCity" data-control="select2" data-placeholder="Select an option">
                                                <option></option>
                                            </select>
                                            <!--end::Select2-->
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
                            <a href="../../demo31/dist/apps/ecommerce/catalog/products.html" id="kt_ecommerce_edit_order_cancel" class="btn btn-light me-5">Cancel</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_edit_order_submit" class="btn btn-primary">
                                <span class="indicator-label">Save Changes</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
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
    $('#search').on('keyup', function() {

        $('#main_table').html('<tr><td colspan="3"><div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div></td></tr>');
        if ($(this).val().length >= 3) {
            $.ajax({
                url: routeAll,
                type: 'GET',
                data: {
                    search: $(this).val(),
                },
                datatype: 'json',
                success: function(data) {
                    if (data) {
                        $('#main_table').html(data);
                    } else {
                        $('#main_table').html('<tr><td colspan="3"><p class="alert alert-danger text-center d-block">No valid data.</p></td></tr>')
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });
        } else {
            $('#main_table').html('<tr><td colspan="3"><p class="alert alert-danger text-center d-block">No of leeters sholud be atleast 3 chars.</p></td></tr>')
        }
    })
</script>
<script>
    var citiesAjaxRoute = "{{ route('ajax.cities') }}"
    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        $.ajax({
            url: citiesAjaxRoute,
            type: 'GET',
            data: {
                'country_id': countryId
            },
            dataType: 'json',
            success: function(data) {
                $('#cities').html(null);
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement('option');
                    console.log(data[i].name.value);
                    option.value = data[i].id;
                    option.text = data[i].name.value;
                    $('#cities').append(option);
                }
            },
            error: function(request, error) {
                console.log(JSON.stringify(request));
            }
        });
    });
</script>
<script>
    function toggleInputField(inputId) {
        console.log(inputId);
        // Get the status of the checkbox.
        var status = $("#checkbox" + inputId).is(":checked");

        // Toggle the state of the input field.
        $('#qty' + inputId).prop("disabled", !status);
    }
</script>
@endpush