<table  class="table align-middle table-row-dashed fs-6 gy-5">
    <thead>
    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
        <th>{{ __('Title') }}</th>
        <th>{{ __('From Date') }}</th>
        <th>{{ __('To Date') }}</th>
        @permission('update_events')
        <th>{{ __('Status') }}</th>
        @endpermission
        <th class="text-end min-w-100px">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse($events as $event)
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <input type="hidden" value="{{ $event->id }}">
                    <!--begin::Thumbnail-->
                    <p class="symbol symbol-50px">
                        <span class="symbol-label" style="background-image:url('@if(count($event->image)){{ getFile($event->image[0]->file,'images',getFileNameServer($event->image[0])) }}@else{{ asset('dashboard') }}/assets/media/svg/files/blank-image.svg @endif')"></span>
                    </p>
                    <!--end::Thumbnail-->
                    <div class="ms-5">
                        <!--begin::Title-->
                        <p class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-event-filter="event_name">{{ Str::limit($event->title, 30) }}</p>
                        <!--end::Title-->
                    </div>
                </div>
            </td>
            <td><p>{{ $event->fromDate }}</p></td>
            <td><p>{{ $event->toDate }}</p></td>
            @permission('update_events')
            <td>
                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid" >
                    <input class="form-check-input" type="checkbox" value="" name="notifications" {{ $event->status ? 'checked' : '' }} onclick="toggleActive({{ $event->id }})">
                    <label class="form-check-label" id="active-label-{{ $event->id }}"> {{ $event->status ? 'Active' : 'Inactive' }}</label>
                </div>
            </td>
            @endpermission
            @canany(['update_events','delete_event'])​
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
                @permission('update_events')
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="{{ route('event.edit', $event->id) }}" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
                            Edit
                        </a>
                    </div>
                    @endpermission

                    <!--end::Menu item-->
                    @permission('delete_event')
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="#" data-kt-event-table-filter="delete_row" class="menu-link px-3">Delete</a>
                    </div>
                    <!--end::Menu item-->
                    @endpermission

                </div>
            </td>
            @else
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
        {!! $events->links() !!}
    </div>
</div>
