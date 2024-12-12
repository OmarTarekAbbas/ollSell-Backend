<div class="card-body pt-0">
    @include('dashboard.error.error')
    @if(count($refunds))

    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_refunds_table" aria-describedby="kt_refunds_table_info" style="width: 1028px;">
        <!--begin::Table head-->
        <thead>
            <!--begin::Table row-->
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <!-- <th class="w-10px pe-2 sorting_disabled sorting_asc" rowspan="1" colspan="1" style="width: 30.5px;" aria-label="">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_refunds_table .form-check-input" value="1">
                    </div>
                </th> -->
                <th tabindex="0" aria-controls="kt_refunds_table" rowspan="1" colspan="1" style="width: 192.25px;" aria-label="Name: activate to sort column ascending">Request ID</th>


                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_refunds_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th>


                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_refunds_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Date</th>

                <th style="text-align: start !important; width: 192.25px;" tabindex="0" aria-controls="kt_refunds_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Visit</th>

                <!-- <th class="text-end" rowspan="1" colspan="1">Actions</th> -->
            </tr>
            <!--end::Table row-->
        </thead>
        <!--end::Table head-->
        <!--begin::Table body-->
        <tbody class="fw-semibold text-gray-600">
            @forelse($refunds as $refund)
            <tr class="odd">
                <!-- <td class="sorting_1">
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="item_check" value="37">
                    </div>
                </td> -->
                <td>#{{$refund->id}}</td>
                
                <td class="text-center" id="status{{$refund->id}}" >
                    <span class="badge {{setStatusClass($refund->status->name->value)}}">{{ getStatusTitle($refund->status->id) }}</span>
                </td>
                <td>
                    <p class="w-150px">{{\Carbon\Carbon::parse($refund->created_at)->translatedFormat('d/m/Y') . '  ' . date("h:i", strtotime($refund->created_at)) . ' ' . date("a", strtotime($refund->created_at))}}</p>
                </td>
                <td>
                    <a href="{{route('order.refund.show', $refund)}}" type="button"
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
    <div class="d-flex justify-content-between">
        <div>
            @include('dashboard.layouts.table_length')
        </div>
        <div>
            {!! $refunds->links() !!}
        </div>
    </div>

    @else
        <h3 class="text-center text-gray">No Records to display ...</h3>
    @endif
</div>