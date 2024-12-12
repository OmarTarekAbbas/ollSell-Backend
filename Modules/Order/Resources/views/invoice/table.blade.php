<div class="card-body pt-0">
    @include('dashboard.error.error')
    @if(count($orders))

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
            @forelse($orders as $order)
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

                {{-- @if(in_array($order->status_id,[getStatusId($order::NEW_STATUS)]))
                <td class="text-end" id="remove{{$order->id}}">
                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                        <span class="svg-icon svg-icon-5 m-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <!--end::Svg Icon--></a>
                    <!--begin::Menu-->
                    <div  class="language-list-actions menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true" style="">
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="#"  onclick="toggleActive({{$order->id}},'approved');return false; "class="menu-link px-3">Approved</a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="#"  onclick="toggleActive({{$order->id}},'refused');return false;" class="menu-link px-3">Refused</a>
                        </div>

                        <!--end::Menu item-->


                    </div>
                    <!--end::Menu-->
                </td>
                @elseif(in_array($order->status_id,[getStatusId($order::PENDING_STATUS)]))
                <td class="text-end" id="remove{{$order->id}}">
                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                        <span class="svg-icon svg-icon-5 m-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <!--end::Svg Icon--></a>
                    <!--begin::Menu-->
                    <div  class="language-list-actions menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true" style="">
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="#"  onclick="toggleActive({{$order->id}},'deliveredAymakan');return false; "class="menu-link px-3">Delivered Aymakan</a>
                        </div>
                        <!--end::Menu item-->

                    </div>
                    <!--end::Menu-->
                </td>
                @else
                <td>No Result</td>
                @endif --}}
            </tr>
            @empty

            <h2>No Result</h2>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <div>
            @include('dashboard.layouts.table_length')
        </div>
        <div>
            {!! $orders->links() !!}
        </div>
    </div>

    @else
        <h3 class="text-center text-gray">No Records to display ...</h3>
    @endif
</div>