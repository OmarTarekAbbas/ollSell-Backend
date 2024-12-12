<!--begin::Repeater-->
<div id="kt_docs_repeater_basic">
    <!--begin::Form group-->
    <div class="form-group">
        <div data-repeater-list="variants">
            @foreach($variations as $index => $variation)
            <div data-repeater-item>
                    <input type="hidden" name="attribute_option_id" value="@foreach ($variation as $var){{  $var['id'] }}{{ !$loop->last ? "," : '' }} @endforeach">
                    <input type="hidden" name="attribute_id" value="@foreach ($variation as $var){{  $var['attribute_id'] }}{{ !$loop->last ? "," : '' }} @endforeach">
                <div class="form-group row mt-5">
                    <div class="col-md-1">
                        <p class="mt-11">#{{ $index +1 }}</p>
                    </div>
                    <div class="col-md-{{ count($variation) +1 }}">
                        <label class="form-label">Name:</label>
                        <input  class="form-control mb-2 text-center"  disabled readonly value="@foreach ($variation as $var) {{  $var['name'] }} {{ !$loop->last ? '/' : '' }} @endforeach"/>
                    </div>
                    {{-- <div class="col-md">
                        <label class="form-label">Price:</label>
                        <input type="number" name="price" class="form-control mb-2 mb-md-0" placeholder="Price" />
                    </div> --}}
                    <div class="col-md">
                        <label class="form-label">Quantity:</label>
                        <input type="number" step="1" min="0" name="quantity" class="form-control mb-2 mb-md-0" placeholder="Quantity"/>
                    </div>
                    <div class="col-md">
                        <label class="form-label">SKU:</label>
                        <input type="text" name="sku" class="form-control mb-2 mb-md-0" placeholder="SKU"/>
                    </div>
                    <div class="col-md">
                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8" onclick="deleteParent(this)">
                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            Delete
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!--end::Form group-->
</div>

<!--end::Repeater-->
<script src="{{ asset('dashboard2')  }}/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<script src="{{ asset('dashboard2')  }}/assets/js/custom/apps/ecommerce/catalog/save-product.js"></script>
<script>
    $('#kt_docs_repeater_basic').repeater({
        initEmpty: false,

        defaultValues: {
            'text-input': 'foo'
        },

        show: function () {
            $(this).slideDown();
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });
</script>
