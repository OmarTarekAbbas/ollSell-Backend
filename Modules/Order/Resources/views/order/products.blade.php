    @foreach ($products as $index => $product)

        <tr>
            <td>
                <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input id="checkbox{{ $product->id }}" onchange="toggleInputField('{{ $product->id }}')" name="items[{{ $index }}][product]" class="form-check-input" type="checkbox" value="{{ $product->id }}" />
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center" data-kt-ecommerce-edit-order-filter="product" data-kt-ecommerce-edit-order-id="product_1">
                    <!--begin::Thumbnail-->
                    <a target="_blank" href="{{ route('product.edit', $product->id) }}" class="symbol symbol-50px">
                        <span class="symbol-label" style="background-image:url(@if(count($product->thumbnail) > 0)  {{ url("images/product/".$product->thumbnail[0]->category_id."/". $product->thumbnail[0]->file) }} @else {{ asset('dashboard') }}/assets/media//stock/ecommerce/1.gif);@endif"></span>
                    </a>
                    <!--end::Thumbnail-->
                    <div class="ms-5">
                        <!--begin::Title-->
                        <a target="_blank" href="{{ route('product.edit', $product->id) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ Str::limit($product->name->value, 30) }}</a>
                        <!--end::Title-->
                        <!--begin::Price-->
                        <div class="fw-semibold fs-7">Price: $
                        <span data-kt-ecommerce-edit-order-filter="price">{{ $product->cost_price  }}</span></div>
                        <!--end::Price-->
                        <!--begin::SKU-->
                        <div class="text-muted fs-7">SKU: {{ $product->sku }}</div>
                        <!--end::SKU-->
                    </div>
                </div>
            </td>
            <td>
                <div class="input-group mb-5">
                    <input id="qty{{ $product->id }}" name="items[{{ $index }}][quantity]" type="number" class="form-control" value="1" aria-describedby="basic-addon1" disabled/>
                </div>
            </td>
            @if($product->quantity > 5)
            {{--Normal  --}}
            <td class="text-end pe-5" data-order="{{ $product->quantity }}">
                <span class="fw-bold ms-3">{{ $product->quantity }}</span>
            </td>
            @endif
            @if($product->quantity <= 5)
            {{-- low stock --}}
            <td class="text-end pe-5" data-order="{{ $product->quantity }}">
                <span class="badge badge-light-warning">Low stock</span>
                <span class="fw-bold text-warning ms-3">{{ $product->quantity }}</span>
            </td>
            @endif
            @if($product->quantity <= 0)

            {{-- Out of stock --}}
            <td class="text-end pe-5" data-order="{{ $product->quantity }}">
                <span class="badge badge-light-danger">Sold out</span>
                <span class="fw-bold text-danger ms-3">{{ $product->quantity }}</span>
            </td>
            @endif
        </tr>
        
    @endforeach
    @push('scripts')
        
    @endpush


        