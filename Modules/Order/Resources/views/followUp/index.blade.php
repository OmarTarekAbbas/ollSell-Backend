@extends($layout)


@section('title', 'Follow-up')

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->


                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            {{-- <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                    </svg>
                </span> --}}
                            <!--end::Svg Icon-->
                            {{-- <input type="text" data-kt-product-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Sub Status"> --}}
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                </div>

                <!--begin::Card toolbar-->
                {{-- <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                <!--begin::Filter-->
                <button type="button" class="btn btn-light-primary me-3  menu-dropdown" data-kt-menu-trigger="click"
                    data-kt-menu-placement="bottom-end">
                    <i class="ki-duotone ki-filter fs-2"><span class="path1"></span><span class="path2"></span></i> Filter
                </button>
                <!--begin::Menu 1-->
                <div class="menu menu-sub menu-sub-dropdown w-500px w-md-500px " data-kt-menu="true"
                    style="z-index: 107; position: fixed; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-256px, 102.4px, 0px);"
                    data-popper-placement="bottom-end">
                    <!--begin::Header-->
                    <div class="px-7 py-5">
                        <div class="fs-4 text-dark fw-bold">Filter Options</div>
                    </div>
                    <!--end::Header-->

                    <!--begin::Separator-->
                    <div class="separator border-gray-200"></div>
                    <!--end::Separator-->

                    <!--begin::Content-->
                    <div class="px-7 py-5" style="position: relative;top: 20px;">
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <!--begin::Label-->
                            <label class="form-label fs-5 fw-semibold mb-3">Search:</label>
                            <!--end::Label-->

                            <!--begin::Options-->
                            <div class="d-flex flex-column flex-wrap fw-semibold">
                                <input type="text" class="form-control" name="search" >
                                <div class="error-message"></div>
                            </div>
                        </div>
                        <!--end::Options-->

                        <div class="d-flex gap-5">
                            <!--begin::Input group-->
                            <div class="mb-3">
                                <!--begin::Label-->
                                <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                                <!--end::Label-->

                                <!--begin::Options-->
                                <div class="d-flex flex-column flex-wrap fw-semibold"
                                    data-kt-docs-table-filter="status_id ">
                                    <select class="form-select" aria-label="Default select status" name="statusId">
                                        <option selected>Open this select menu</option>

                                    </select>
                                </div>
                            </div>
                            <!--end::Options-->

                        </div>


                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end mb-4">
                            <button type="reset" class="btn btn-light btn-active-light-primary me-2">Reset</button>

                            <button type="submit" class="btn btn-primary apply-filter-button">Apply</button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Menu 1--> <!--end::Filter-->

            </div>
        </div> --}}
                <!--end::Card toolbar-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div id="kt_product_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                id="kt_status_table" aria-describedby="kt_product_table_info">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px sorting_disabled text-center" rowspan="1" colspan="1"
                                            aria-label="Name"># Order ID</th>
                                        <th class="min-w-125px text-end sorting_disabled text-center" rowspan="1"
                                            colspan="1" aria-label="Active">Last activity</th>
                                        <th class="min-w-125px sorting text-center" tabindex="0"
                                            aria-controls="kt_product_table" rowspan="1" colspan="1"
                                            style="width: 207.25px;" aria-label="SKU: activate to sort column ascending">
                                            Scheduled</th>
                                        <th class="min-w-125px sorting text-center" tabindex="0"
                                            aria-controls="kt_product_table" rowspan="1" colspan="1"
                                            style="width: 207.25px;" aria-label="SKU: activate to sort column ascending">
                                            Number
                                            of Attempts</th>
                                        <th class="text-end min-w-70px sorting_disabled text-center" rowspan="1"
                                            colspan="1" style="width: 161.25px;" aria-label="Actions">Date added</th>
                                        <th class="text-end min-w-70px sorting_disabled text-center" rowspan="1"
                                            colspan="1" style="width: 161.25px;" aria-label="Actions">Actions</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-semibold text-gray-600">
                                    @forelse($ordersWithFollowUp as $order)

                                        @php
                                            $lastScheduledDate = $order->firstUnsatisfiedSchedule
                                                ? \Carbon\Carbon::parse(
                                                    $order->firstUnsatisfiedSchedule?->scheduled_date,
                                                )
                                                : null;
                                            $formattedScheduledDate = optional($lastScheduledDate)->format('Y-m-d');
                                        @endphp

                                        <tr class="odd">
                                            <td class="text-center">
                                                <a href="{{ route('order.show', $order) }}">{{ $order->id }}</a>
                                            </td>
                                            <td class="text-center">
                                                {{ $order->followUps->last()?->created_at->format('Y-m-d') ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if ($lastScheduledDate)
                                                    @if ($lastScheduledDate->isPast())
                                                        <span
                                                            class="badge badge-danger">{{ $formattedScheduledDate }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-success">{{ $formattedScheduledDate }}</span>
                                                    @endif
                                                @else
                                                    <span>No scheduled date</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info">
                                                    {{ $order->followUps()->where('activity_type', '!=', 'Initiated')->count() }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                {{ Carbon\Carbon::parse($order->follow_order)->format('Y-m-d H:i:s') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('order.followUp.show', $order) }}" type="button"
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
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">No records yet</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            <div id="kt_product_table_processing" class="dataTables_processing" style="display: none;">
                                <div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!--end::Datatable-->

            </div><!--end::Products-->
        </div>
    </div>
@endsection


@push('scripts')
@endpush

@section('second-sidebar')
    @include('order::layouts.sidebar')
@endsection
