<div class="card-body pt-0">
    @include('dashboard.error.error')

    @if (isset($withdrawalRequests))
        @if (count($withdrawalRequests))

            <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_orders_table"
                aria-describedby="kt_orders_table_info" style="width: 1028px;" >
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2 sorting_disabled sorting_asc" rowspan="1" colspan="1"
                            style="width: 30.5px;" aria-label="">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true"
                                    data-kt-check-target="#kt_orders_table .form-check-input" value="1">
                            </div>
                        </th>
                        <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_orders_table" rowspan="1"
                            colspan="1"
                            aria-label="Name: activate to sort column ascending">ID</th>

                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_orders_table" rowspan="1"
                            colspan="1" style="width: 350.25px;"
                            aria-label="Email: activate to sort column ascending">Dropshipper</th>
                        <th class="min-w-50px">Dropshipper ID</th>
                        <th class="min-w-50px">Dropshipper Phone</th>
                        <th class="min-w-50px">Total Amount Dropshipper</th>
                        <th class="min-w-50px">Withdraw Dropshipper</th>
                        <th class="min-w-50px">Balance Dropshipper</th>
                        <th class="min-w-125px sorting" style="text-align: start !important; width: 192.25px;"
                            tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1"
                            aria-label="Status: activate to sort column ascending">Status</th>

                        <th class="min-w-125px sorting" style="text-align: start !important; width: 192.25px;"
                            tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1"
                            aria-label="Status: activate to sort column ascending">Amount</th>

                        <th class="min-w-125px sorting" style="text-align: start !important; width: 192.25px;"
                            tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1"
                            aria-label="Status: activate to sort column ascending">Reason</th>
                        {{-- <th class="min-w-125px sorting" style="text-align: start !important; width: 192.25px;"
                            tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1"
                            aria-label="Status: activate to sort column ascending">Date</th> --}}

                        <th class="text-end min-w-70px" rowspan="1" colspan="1">Actions</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-semibold text-gray-600">
                    @forelse($withdrawalRequests as $order)
                        <tr class="odd" id="row{{ $order->id }}">
                            <td class="sorting_1">
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="item_check" value="37">
                                </div>
                            </td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->dropshipper->first_name . ' ' . $order->dropshipper->second_name }}</td>
                            <td>{{ $order->dropshipper_id }}</td>
                            <td>{{ $order->dropshipper->phone }}</td>
                            <td>{{ $order->total_amount_dropshipper}}</td>
                            <td>{{ $order->withdraw_dropshipper}}</td>
                            <td>{{ $order->balance_dropshipper}}</td>
                            <td style="color: {{ status($order->status) }}" id="status{{ $order->id }}">
                                {{ $order->status }} </td>
                            <td>{{ $order->amount }}</td>
                            <td id="reason{{ $order->id }}">{{ $order->reason ?? '-' }}</td>
                            <td>
                                <a href=" {{ route('withdrawalRequest.show', $order->id) }}"
                                    class="btn btn-light btn-active-light-primary btn-sm">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <h2>No Result</h2>
                    @endforelse
                </tbody>

            </table>
            {{ $withdrawalRequests->appends($_GET)->links('dashboard.layouts.pagination', ['paginator' => $withdrawalRequests, 'perPage' => Request::get('perPage') ?? $withdrawalRequests->perPage()]) }}
        @else
            <h3 class="text-center text-gray">No Records to display in this time range ...</h3>
        @endif
    @else
        <h3 class="text-center text-gray">No Records to display in this time range ...</h3>
    @endif
</div>
