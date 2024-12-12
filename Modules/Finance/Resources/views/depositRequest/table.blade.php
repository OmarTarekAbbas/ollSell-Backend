<div class="card-body pt-0">
    @include('dashboard.error.error')
    @if(count($depositRequests))

    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_orders_table" aria-describedby="kt_orders_table_info" style="width: 1028px;">
        <!--begin::Table head-->
        <thead>
            <!--begin::Table row-->
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2 sorting_disabled sorting_asc" rowspan="1" colspan="1" style="width: 30.5px;" aria-label="">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_orders_table .form-check-input" value="1">
                    </div>
                </th>
                <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 192.25px;" aria-label="Name: activate to sort column ascending">ID</th>

                <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 350.25px;" aria-label="Email: activate to sort column ascending">dropshipper</th>

                <th class="min-w-125px sorting" style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th>

                <th class="min-w-125px sorting" style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Amount</th>

                <th class="min-w-125px sorting" style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Date</th>

                <th class="text-end min-w-70px" rowspan="1" colspan="1">Actions</th>
            </tr>
            <!--end::Table row-->
        </thead>
        <!--end::Table head-->
        <!--begin::Table body-->
        <tbody class="fw-semibold text-gray-600">
            @forelse($depositRequests as $deposit)
            <tr class="odd" id="row{{$deposit->id}}">
                <td class="sorting_1">
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="item_check" value="37">
                    </div>
                </td>
                <td>{{$deposit->id}}</td>
                <td>
                    <a href="{{route('dropshipper.show', $deposit->dropshipper)}}">{{$deposit->dropshipper->first_name .' '. $deposit->dropshipper->second_name}}</a>
                </td>
                <td style="color: {{status($deposit->status)}}" id="status{{$deposit->id}}" >{{$deposit->status}} </td>
                <td>{{$deposit->amount }}</td>

                <td>{{\Carbon\Carbon::parse($deposit->created_at)->translatedFormat('l d F Y') . ' in ' . date("h:i", strtotime($deposit->created_at)) . ' ' . date("a", strtotime($deposit->created_at))}}
                <td class="text-end" id="removebutton{{$deposit->id}}">
                    <a href="{{route('depositRequest.show', $deposit)}}" class="btn btn-light btn-active-light-primary btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty

            <h2>No Result</h2>
            @endforelse
        </tbody>
    </table>
{{$depositRequests->appends($_GET)->links('dashboard.layouts.pagination', ['paginator' => $depositRequests,'perPage' =>Request::get('perPage') ?? $depositRequests->perPage()]) }}

@else
    <h3 class="text-center text-gray">No Records to display in this time range ...</h3>
@endif
</div>
