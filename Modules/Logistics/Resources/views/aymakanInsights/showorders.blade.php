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

                <th tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 350.25px;" aria-label="Email: activate to sort column ascending">Tracking                </th>

                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Order Creation
                </th>

                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Order Status                </th>

                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">City
                </th>

                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">SLA
                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Total Transactions
                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Submittion Date

                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Received AtHub

                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Delivery Type
                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">External Creation
                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">First Delivery
                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Last Delivery

                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Delivery Attempts

                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">No Answer Count


                </th>
                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Is Future Delivery



                </th>
                <th>LastUpdateDate
                </th>
                <th>LastStatus

                </th>
                <th>Last Update message 

                </th>
                <th>RTFD
                </th>
                <th>FDTLD
                </th>
                <th>OVERALL

                </th>
                <th class="text-end" rowspan="1" colspan="1">Actions</th> 
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
                <td>{{$order->tracking_number}}</td>
                <td>
                    <p class="w-150px">{{\Carbon\Carbon::parse($order->created_at)->translatedFormat('d/m/Y') . '  ' . date("h:i", strtotime($order->created_at)) . ' ' . date("a", strtotime($order->created_at))}}</p>
                </td>
                <td class="text-center" id="status{{$order->id}}" >
                    <span class="badge {{setStatusClass($order->status->name->value)}}">{{ getStatusText($order->status?->name?->value) }}</span>
                </td>
             
             
                <td> {{ $order->city?->name?->value }}</td>

                <td>{{$order->sla}}</td>
                <td>{{$order->TotalTransactions}}</td>
                <td>{{$order->SubmittionDate}}</td>
                <td>{{$order->ReceivedAtHub}}</td>
                <td>{{$order->DeliveryType}}</td>
                <td>{{$order->ExternalCreation}}</td>
                <td>{{$order->FirstDelivery}}</td>
                <td>{{$order->LastDelivery}}</td>
                <td>{{$order->DeliveryAttempts}}</td>
                
                <td>{{$order->NoAnswerCount}}</td>
                <td>{{$order->IsFutureDelivery}}</td>
                <td>{{$order->LastUpdateDate}}</td>
                <td>{{$order->LastStatus}}</td>
                <td>{{$order->LastUpdate}}</td>
                <td>{{$order->RTFD}}</td>
                <td>{{$order->FDTLD}}</td>
                <td>{{$order->OVERALL}}</td>
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