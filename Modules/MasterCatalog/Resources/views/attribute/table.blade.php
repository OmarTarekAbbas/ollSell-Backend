<table  class="table align-middle table-row-dashed fs-6 gy-5">
    <thead>
    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
        <th>{{ __('Name') }}</th>
        @permission('update_attribute')
        <th>{{ __('Status') }}</th>
        @endpermission
        <th class="text-end min-w-100px">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse($attributes as $attribute)
        <tr>
            <td>
                <input type="hidden" value="{{ $attribute->name }}">
                <div class="d-flex align-items-center">
                    <input type="hidden" value="{{ $attribute->id }}">
                    <div class="ms-5">
                        <!--begin::Title-->
                        <p class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-attribute-filter="attribute_name">{{ Str::limit($attribute->name, 30) }}</p>
                        <!--end::Title-->
                    </div>
                </div>
            </td>
            @permission('update_attribute')
            <td>
                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid" >
                    <input class="form-check-input" type="checkbox" value="" name="notifications" {{ $attribute->status ? 'checked' : '' }} onclick="toggleActive({{ $attribute->id }})">
                    <label class="form-check-label" id="active-label-{{ $attribute->id }}"> {{ $attribute->status ? 'Active' : 'Inactive' }}</label>
                </div>
            </td>
            @endpermission
            @canany(['update_attribute','delete_attribute'])​
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
                @permission('update_attribute')
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="{{ route('attribute.edit', $attribute->id) }}" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
                            Edit
                        </a>
                    </div>
                    <!--end::Menu item-->
                    @endpermission
                    @permission('delete_attribute')
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="#" data-kt-attribute-table-filter="delete_row" class="menu-link px-3">Delete</a>
                    </div>
                    <!--end::Menu item-->
                    @endpermission

                </div>
            </td>
            ​ @else
            <td class="text-center">
                ---
            </td>
            @endcanany
        </tr>
        @empty
            <tr>
                <td colspan="7">
                    <div class="alert alert-danger text-center"><h3 class="text-center text-gray">No Records to display...</h3></div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-between">
    <div class="d-flex">
        @include('dashboard.layouts.table_length')
    </div>
    <div>
        {!! $attributes->links() !!}
    </div>
</div>
