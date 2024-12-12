<table class="table align-middle table-row-dashed fs-6 gy-5">
    <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th class="w-10px pe-2">

            </th>
            <th class="min-w-125px">{{ __('Name') }}</th>
            <th class="min-w-125px">{{ __('Country') }}</th>
            <th class="min-w-125px">{{ __('City') }}</th>
            <th class="min-w-125px text-center">{{ __('Location') }}</th>
            <th class="min-w-70px text-center">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
    @if(!empty($warehouseIsInternal))
        <tr style="background: #f2efef;">
            <td>
                <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="hidden" name="item_check" value="{{ $warehouseIsInternal->id }}" />
                </div>
            </td>
            <td>{{ $warehouseIsInternal->name }}</td>
            <td>
                <p>{{$warehouseIsInternal->country->name?->value}}</p>
            </td>
            <td>
                <p>{{$warehouseIsInternal->city->name?->value}}</p>
            </td>
            <td class="text-center">
                <a>-</a>
            </td>
            <td class="text-center">
                <a>-</a>
            </td>
        </tr>
        @endif
        @forelse($warehouses as $warehouse)
        <tr @if($warehouse->is_admin == 1) style="background: #f2efef;" @endif>
            <td>
                <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="hidden" name="item_check" value="{{ $warehouse->id }}" />
                </div>
            </td>
            <td><a href="{{ route('supplier.warehouse.show', $warehouse->id) }}">{{ $warehouse->name }}</a></td>
            <td>
                <p>{{ $warehouse->country->name?->value }}</p>
            </td>
            <td>
                <p>{{ $warehouse->city->name?->value }}</p>
            </td>
            <td class="text-center">
                @if($warehouse->location)
                <a target="_blank" href="{{$warehouse->location}}">Show map <i class="fa-solid fa-location-dot"></i></a>
                @else
                <a>-</a>
                @endif
            </td>
            @if($warehouse->is_admin == 1)
            <td class="text-center">
                <a>-</a>

            </td>
            @else
            <td class="text-center">
                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                    <span class="svg-icon svg-icon-5 m-0">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon--></a>
                <!--begin::Menu-->
                <div class="users-list-actions menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="{{ route('supplier.warehouse.show', $warehouse->id) }}" class="menu-link px-3">View</a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="{{ route('supplier.warehouse.edit', $warehouse->id) }}" class="menu-link px-3">Edit</a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="#" data-kt-users-table-filter="delete_row" class="menu-link px-3">Delete</a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::Menu-->
            </td>
            @endif

        </tr>
        @empty
        <tr>
            <td colspan="9">
                <div class="alert alert-danger text-center">
                    <h3 class="text-center text-gray">No Records to display...</h3>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-between">
    <div>
        {{-- @include('dashboard.layouts.table_length') --}}
    </div>
    <div>
        {!! $warehouses->links() !!}
    </div>
</div>
