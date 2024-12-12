@extends($layout)


@section('title', 'Bundles')

@section('content')

    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_city_edit"
            aria-expanded="true" aria-controls="kt_city_edit">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">bundle</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            @include('dashboard.error.error')
            <form id="kt_bundle_edit_form" class="form" method="post" action="{{ route('bundles.update', $data->id) }}">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top p-9">

                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">SKU</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="sku" value="{{ $data->sku }}"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="SKU" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>

                    <!--begin::Input group-->
                    @foreach (language() as $lang)
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Name
                                {{ $lang->code }}</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <input type="text" name="name[{{ $lang->code }}]"
                                            value="{{ $data->nameValue($lang) }}"
                                            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                            placeholder="Name {{ $lang->code }}" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>

                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Description
                                {{ $lang->code }}</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <textarea name="description[{{ $lang->code }}]" id="kt_docs_ckeditor_classic_{{$lang->code}}1">{{ Request::old('description') ? Request::old('description')[$lang->code] : $data->descriptionValue($lang) }}</textarea>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                    @endforeach
                    <!--end::Input group-->



                </div>
                <div class="card card-flush py-4">
                    <!--begin::Card header-->

                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">Related Dropshipper
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <select id="bundle_dropshippers" name="bundle_dropshippers[]"
                                                class="form-select form-select-solid form-select-lg fw-semibold"
                                                data-mce-placeholder="" multiple>
                                            @foreach($data->bundle_dropshippers as $related)
                                                <option selected value="{{$related->dropshipper_id}}"> {{$related->dropshipper->email}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label  fw-semibold fs-6">Discount
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <input type="number" name="discount" value="{{ $data->discount }}"
                                            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                            placeholder="Discount" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>


                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Total Price
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <input type="number" name="cost_price" min="0" id="cost_price"
                                            value="{{ $data->cost_price }}" readonly
                                            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                            placeholder="Total Price" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>

                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Total Quantity
                              </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6 fv-row">
                                        <input type="number" name="quantity" id="total_quantity" min="0"
                                            value="{{ $data->quantity }}" readonly
                                            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                            placeholder="Total Quantity" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>

                    </div>

                </div>

                <div class="card card-flush py-4">
                    <div class="card-body pt-0" >
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_product_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th >
                                Product
                                </th>
                                <th >
                                SKU
                                </th>
                                <th >
                                Price
                                </th>
                                <th >
                                Quantity
                                </th>
                                 <th >
                                Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                             <tr>
                                <td colspan="4">
                                    <button class="btn btn-success btn-sm" id="addTableRow">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
                <!--end::Input group-->
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('bundles.index') }}"
                        class="btn btn-light btn-active-light-primary me-2">Discard</a>
                    <button type="submit" class="btn btn-primary" id="kt_bundle_edit_submit">Save Changes
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
@push('scripts')
<script>

    @if(!empty($data->products))
        @foreach($data->products as $item)
            $('#kt_product_table tbody').append(tableRow({!!$item!!}));
        @endforeach
    @endif







    function tableRow(row = null) {

        var price = 0,
            Quantity = 0,
            count = 0,
            product_id = '',
            sku = '';

        if (row != null) {
            product_id = row.product_id;
            count = row.count;
        }

        if (product_id) {
            var data = productInput(product_id);
            var item_input = data[0];
            sku = data[1];
            price = data[2];
            Quantity = data[3];
        } else {
            var item_input = productInput(product_id);
        }

        var rowHtml = '<tr>' +
            '<td>' +
            item_input +
            '</td>' +
            '<td>' +
            '<input name="bundle_products[sku][]" type="text" class="form-control" placeholder="SKU" value="' + sku + '" disabled>' +
            '</td>' +
            '<td>' +
            '<input  name="bundle_products[price][]" type="number" readonly class="form-control calculateTotal" value="' + price + '">' +
            '</td>' +
            '<td>' +
            '<input readonly name="bundle_products[Quantity][]"  type="text" class="form-control calculateTotal" value="' + Quantity + '">' +
            '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-danger deleteRecord"><i class="fa fa-trash"></i></button>' +
            '</td>' +
            '</tr>';

        return rowHtml;
    }


    function productInput(selectedOption = '') {
        var price = 0,
            Quantity = 0,
        sku = '';

        var route = "{!! route('product.search') !!}";
        var input = '<select  name="bundle_products[product_id][]"  class="form-select form-select-solid form-select-lg  fw-semibold item " data-control="select2" aria-label="Select a Product"  data-placeholder="Select a Product..." >';

        input += '<option selected></option>';
        $.ajax({
            type: "Get",
            url: route,
            async: false,
            dataType: 'json', // Define data type will be JSON
            data: {
                is_edit_bundle: 1,
            },
            success: function(result) {
                var options = result;

                $.each(options, function(index, option) {
                    var selected = '';
                    if (selectedOption == option.id) {
                        selected = 'selected';
                        price = option.cost_price;
                        Quantity = option.quantity;
                        sku = option.sku;

                    }
                    input += '<option ' + selected + ' value="' + option.id + '" data-sku=\'' + option.sku + '\' data-quantity=\'' + option.quantity + '\' data-price=\'' + option.cost_price + '\'>' + option.name.value + '</option>';
                });

            },
            error: function(error) {
                $("#ajaxResponse").append("<div>" + error + "</div>");
            }
        });
        input += '</select>';

        if (selectedOption) {
            var data = [input, sku, price, Quantity];
            return data;
        }

        calculateTotal();
        return input;

    }


    $(document).on('change', '.item', function(e) {

        e.preventDefault();

        var sku = $(this).find('option:selected').attr('data-sku');
        var quantity = $(this).find('option:selected').attr('data-quantity');
        var price = $(this).find('option:selected').attr('data-price');

        // Check if SKU already exists in the table
        var skuExists = false;
        $('#kt_product_table tbody tr').each(function() {
            var existingSku = $(this).find('input[name="bundle_products[sku][]"]').val();
            if (existingSku === sku) {
                skuExists = true;
                return false; // Break the loop
            }
        });

        if (skuExists) {
            alert('This product is already added to the table.');
            $(this).closest('tr').remove(); // Delete the entire row if duplicate
            return; // Exit the function if SKU exists
        }

        $(this).parents('tr').find('input[name="bundle_products[sku][]"]').val(sku);
        $(this).parents('tr').find('input[name="bundle_products[price][]"]').val(price);
        $(this).parents('tr').find('input[name="bundle_products[Quantity][]"]').val(quantity);

        calculateTotal();
    });


    function calculateTotal() {
        var total = 0;
        var minQuantity = Infinity; // Initialize to a large number

        $('#kt_product_table > tbody  > tr').each(function(index, tr) {
            var item = $(this).find('select[name="bundle_products[product_id][]"]').find('option:selected').val();
            var amount = $(this).find('input[name="bundle_products[price][]"]').val();
            var quantity = $(this).find('input[name="bundle_products[Quantity][]"]').val(); // Get the quantity

            // Update minQuantity if a valid quantity is found
            if (item !== undefined && item !== '' && quantity) {
                total = parseFloat(total) + parseFloat(amount);
                minQuantity = Math.min(minQuantity, parseInt(quantity)); // Find the minimum quantity
            }
        });

        // Set total_quantity to the minimum quantity found
        $('#total_quantity').val(minQuantity === Infinity ? 0 : minQuantity); // If no products, set to 0

        var discount = $('input[name="discount"]').val();
        if (discount) {
                if (discount > {{(int)setting('bundle_discount')}}) {
                    alert("لا يمكن ان تكون نسبه الخصم اكبر {{(int)setting('bundle_discount')}}");
                    return false;
                }
                let discountAmount = (parseFloat(total) * parseFloat(discount)) / 100;
                total = parseFloat(total) - parseFloat(discountAmount);
            }

        $("#cost_price").val(Math.ceil(total));
    }
    $(document).ready(function() {

        $("#discount").on("input", function() {
            calculateTotal();
        });

        $('#bundle_dropshippers').select2({
            placeholder: "{{ 'select id or name' }}...",
            ajax: {
                url: "{{ route('dropshipper.search') }}",
                method: 'get',
                dataType: 'json',
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.email
                            }
                        }),
                    };
                },
            }
        });
        $('.item').select2({
            placeholder: "{{ 'select sku' }}...",
            ajax: {
                url: "{{ route('product.search') }}",
                method: 'get',
                dataType: 'json',
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.name.value
                            }
                        }),
                    };
                },
            }
        });
        calculateTotal();
    });
    $(document).on('click', '#addTableRow', function(e) {
        e.preventDefault();
        $('#kt_product_table tbody').append(tableRow());
        $('.item').select2({
            placeholder: "{{ 'select sku' }}...",
            ajax: {
                url: "{{ route('product.search') }}",
                method: 'get',
                dataType: 'json',
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.name.value
                            }
                        }),
                    };
                },
            }
        });
    });
    $(document).on('click', '.deleteRecord', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        calculateTotal();
    });
</script>
@endpush
@section('second-sidebar')
@include('coredata::layouts.sidebar')
@endsection