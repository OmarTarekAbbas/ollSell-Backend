<div class="card-body pt-0">
    @include('dashboard.error.error')
    @if(count($orders['ordersOut']))

    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_orders_table" aria-describedby="kt_orders_table_info" style="width: 1028px;">
        <!--begin::Table head-->
        <thead>
            <!--begin::Table row-->
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <!-- <th class="w-10px pe-2 sorting_disabled sorting_asc" rowspan="1" colspan="1" style="width: 30.5px;" aria-label="">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_orders_table .form-check-input" value="1">
                    </div>
                </th> -->
                <th tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 192.25px;" aria-label="Name: activate to sort column ascending">Order ID</th>

                <th tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 350.25px;" aria-label="Email: activate to sort column ascending">Dropshipper</th>

                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th>

                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Sub Total</th>

                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Date</th>

                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">shipping Fees</th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Visit</th>

                <!-- <th class="text-end" rowspan="1" colspan="1">Actions</th> -->
            </tr>
            <!--end::Table row-->
        </thead>
        <!--end::Table head-->
        <!--begin::Table body-->
        <tbody class="fw-semibold text-gray-600">
            @forelse($orders['ordersOut'] as $order)
            <tr class="odd">
                <!-- <td class="sorting_1">
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="item_check" value="37">
                    </div>
                </td> -->
                <td>#{{$order->id}}</td>
                <td class="d-flex align-items-center">
                    <!--begin:: Avatar -->
                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                        <p>
                            <div class="symbol-label">
                                <img src="{{ $order->dropshipper->avatar ? $order->dropshipper->avatar : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}"  class="w-100" />
                            </div>
                        </p>
                    </div>
                    <!--end::Avatar-->
                    <!--begin::User details-->
                    <div class="d-flex flex-column">
                        <p class="text-gray-800 text-hover-primary mb-1">{{ucfirst($order->dropshipper->first_name) .' '. ucfirst($order->dropshipper->second_name)}}</p>
                    </div>
                    <!--begin::User details-->           
                </td>
                <td class="text-center" id="status{{$order->id}}" >
                    <span class="badge {{setStatusClass($order->status->name->value)}}">{{ ucfirst($order->status->name->value) }}</span>
                </td>
                <td>{{$order->subTotal . currency()}}</td>
                <td>
                    <p class="w-150px">{{\Carbon\Carbon::parse($order->created_at)->translatedFormat('d/m/Y') . '  ' . date("h:i", strtotime($order->created_at)) . ' ' . date("a", strtotime($order->created_at))}}</p>
                </td>
                <td>{{$order->shippingFees . currency()}}</td>
                <td>
                    <a href="{{route('order.show', $order->id)}}" type="button"
                       class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                       data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <!--begin::Svg Icon | path: icons/duotune/coding/cod007.svg-->
                        <span class="svg-icon svg-icon-5 m-0">
                            <i class="fa fa-eye"></i>
                        </span>
                        <!--end::Svg Icon-->
                    </a>
                </td>

                
            </tr>
            @empty

            <h2>No Result</h2>
            @endforelse
        </tbody>
    </table>


    @else
        <h3 class="text-center text-gray">No Records to display ...</h3>
    @endif
</div>