<div class="card-body pt-0">
    @include('dashboard.error.error')
    @if(count($features))

    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_orders_table" aria-describedby="kt_orders_table_info" style="width: 1028px;">
        <!--begin::Table head-->
        <thead>
            <!--begin::Table row-->
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <th tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 192.25px;" aria-label="Name: activate to sort column ascending">feature ID</th>

                <th tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 350.25px;" aria-label="Email: activate to sort column ascending">Name</th>
                <th tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 350.25px;" aria-label="Email: activate to sort column ascending">Monthly Price</th>
                <th tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 350.25px;" aria-label="Email: activate to sort column ascending">Yearly Price</th>
                <th tabindex="0" aria-controls="kt_orders_table" rowspan="1" colspan="1" style="width: 350.25px;" aria-label="Email: activate to sort column ascending">Free</th>
                <th class="text-end min-w-100px">{{ __('Actions') }}</th>
                <!-- <th class="text-end" rowspan="1" colspan="1">Actions</th> -->
            </tr>
            <!--end::Table row-->
        </thead>
        <!--end::Table head-->
        <!--begin::Table body-->
        <tbody class="fw-semibold text-gray-600">
            @forelse($features as $feature)
            <tr class="odd">

                <td>#{{$feature->id}}</td>
                <td>{{$feature->name->value}}</td>
                
                
                <td class="text-end">
                    <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                        Actions
                        <span class="svg-icon fs-5 m-0">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                    <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                </g>
                            </svg>
                        </span>
                    </a>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('feature.edit', $feature) }}" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
                                Edit
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        {{-- <div class="menu-item px-3">
                            <a href="#" data-kt-product-table-filter="delete_row" class="menu-link px-3">Delete</a>
                        </div> --}}
                        <!--end::Menu item-->
                    </div>
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
            {!! $features->links() !!}
        </div>
    </div>

    @else
        <h3 class="text-center text-gray">No Records to display ...</h3>
    @endif
</div>
