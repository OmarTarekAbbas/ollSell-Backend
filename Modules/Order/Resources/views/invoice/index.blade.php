@extends($layout)


@section('title', 'Invoices')

@push('styles')
<style>
    .swal2-container .swal2-html-container {
        max-height: 500px !important;
    }

    .pop_button:hover {
        cursor: pointer;
    }

    .modal-content {
        width: 130%;
    }
</style>
@endpush

@section('content')
<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Products-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    {{-- <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <input type="text" data-kt-docs-table-filter="search"
                                class="form-control form-control-solid w-250px ps-15" placeholder="Search Orders" />
                        </div> --}}
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                        <!--begin::Filter-->
                        <button type="button" class="btn btn-light-primary me-3  menu-dropdown" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-filter fs-2"><span class="path1"></span><span class="path2"></span></i> Filter
                        </button>
                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-500px w-md-500px " data-kt-menu="true" style="z-index: 107; position: fixed; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-256px, 102.4px, 0px);" data-popper-placement="bottom-end">
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
                                    <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-docs-table-filter="search ">
                                        <input type="text" class="form-control" name="search" id="exampleFormControlTextarea1">
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                                <!--end::Options-->
                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end mb-4">
                                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-docs-table-filter="reset">Reset</button>

                                    <button type="submit" class="btn btn-primary apply-filter-button" data-kt-menu-dismiss="true" data-kt-docs-table-filter="filter">Apply</button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Menu 1--> <!--end::Filter-->

                    </div>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-start">Invoice ID</th>
                            <th class="text-start">Invoice Number</th>
                            <th class="text-start">Order ID</th>
                            <th class="text-start">payment Method</th>
                            <th class="text-start">PDF Link</th>
                            <th class="text-start">grand Total</th>
                        </tr>
                    </thead>

                    <tbody class="fw-semibold text-gray-600">
                        @forelse($invoices as $invoice)
                        <tr class="odd">

                            <td>#{{$invoice->id}}</td>
                            <td>{{$invoice->invoice_number}}</td>
                            <td>
                                <a target="blank" href="{{route('order.show', ['id' => $invoice->order_id])}}">{{$invoice->order_id}}</a>

                            </td>
                            <td>
                                <?php
                                $paymentMethod =  Modules\Order\PaymentMethod\PaymentMethodList::list()->where('id', $invoice->paymentMethod)->first();
                                ?>
                                {{$paymentMethod['name']}}
                            </td>
                            <td>

                                @if($invoice->pdf_link)
                                <a target="blank" href="{{url('/api/invoice/download/' . $invoice->order_id)}}">
                                    Invoice PDF
                                </a>
                                @else
                                <p>-----</p>
                                @endif

                            </td>
                            <td>{{$invoice->grandTotal . currency()}}</td>

                        </tr>
                        @empty

                        <h2>No Result</h2>
                        @endforelse
                    </tbody>

                </table>

                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Products-->
    </div>
    <!--end::Content container-->
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modaldata">

        </div>
    </div>
</div>



<!--end::Content-->
@endsection
@section('second-sidebar')
@include('order::layouts.sidebar')
@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script src="{{ asset('dashboard2/assets/js/datatables/orders.js') }}"></script>
<script src="{{ asset('dashboard2/assets/js/order/orderStatus.js') }}"></script>

@endpush