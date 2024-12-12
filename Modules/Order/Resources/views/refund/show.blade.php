@extends($layout)


@section('title', 'Order Refund Details')

@section('content')
<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">

            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-fluid ">
                <!--begin::Invoice 2 main-->
                <div class="card">
                    <!--begin::Body-->
                    <div class="card-body p-lg-20">
                        <!--begin::Layout-->
                         <!--begin::Top-->
                        <div class="d-flex flex-stack pb-10">
                            <div>
                                <h3>#{{$refund->id}} - Order Item Refund Request</h3>
                                @if($refund->reason)
                                <p>Reason: {{$refund->reason}}</p>
                                @endif
                            </div>
                            <!--begin::Action-->
                            <!-- If status is still requested -->
                            @permission('update_order')
                            <div>
                                @if($refund->status_id == 6)
                                    <a href="{{route('order.refund.action', ['id' => $refund, 'action' => 'refundApproved'])}}" class="btn btn-sm btn-success">Accept</a>
                                    <a href="{{route('order.refund.action', ['id' => $refund, 'action' => 'refundCancelled'])}}" class="btn btn-sm btn-danger">Refuse</a>
                                @elseif($refund->status_id == 7)
                                    <a href="{{route('order.refund.startShipping', ['id' => $refund, 'type' => 'refundReplacement'])}}" class="btn btn-sm btn-success">Start Shipping replacement</a>
                                    <a href="{{route('order.refund.refundBalance', ['id' => $refund, 'type' => 'refundBalance'])}}" class="btn btn-sm btn-success">Refund Balance</a>
                                @elseif($refund->status_id == 9)
                                    <span class="badge badge-success">
                                        <i class="ki-outline ki-delivery-time fs-1 text-white" style="margin-right:5px"></i>
                                        Order In Shipping
                                    </span>
                                @elseif($refund->status_id == 8)
                                    <span class="badge badge-danger">{{getStatusTitle($refund->status_id)}}</span>
                                @else
                                    <span class="badge badge-success">{{getStatusTitle($refund->status_id)}}</span>
                                @endif
                            </div>
                            @endpermission

                            <!--end::Action-->
                        </div>
                        <!--end::Top-->
                        <div class="d-flex flex-column flex-xl-row">
                            <!--begin::Content-->
                            <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                                <!--begin::Invoice 2 content-->
                                <div class="mt-n1">
                                   

                                    <!--begin::Wrapper-->
                                    <div class="m-0">
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-3 text-gray-800 mb-8">Order #{{$refund->order->id}}</div>
                                        <!--end::Label-->

                                        <!--begin::Row-->
                                        <div class="row g-5 mb-11">
                                            <!--end::Col-->
                                            <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue Date:</div>
                                                <!--end::Label-->

                                                <!--end::Col-->
                                                <div class="fw-bold fs-6 text-gray-800">{{ $refund->created_at->format('Y-m-d H:i')}}</div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Col-->

                                           <!--end::Col-->
                                           <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue For:</div>
                                                <!--end::Label-->

                                                <!--end::Text-->
                                                <div class="fw-bold fs-6 text-gray-800">{{$refund->order->customerName}}</div>
                                                <!--end::Text-->

                                                <!--end::Description-->
                                                <!-- <div class="fw-semibold fs-7 text-gray-600">
                                                    Phone: {{$refund->order->customerPhone}}<br>
                                                    Livonia, MI 48150
                                                </div> -->
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->

                                        <!--begin::Row-->
                                        <div class="row g-5 mb-11">
                                            <!--end::Col-->
                                            <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Customer Phone:</div>
                                                <!--end::Label-->

                                                <!--end::Col-->
                                                <div class="fw-bold fs-6 text-gray-800">{{$refund->order->customerPhone}}</div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Col-->

                                           <!--end::Col-->
                                           <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Address</div>
                                                <!--end::Label-->

                                                <!--end::Text-->
                                                <div class="fw-bold fs-6 text-gray-800">{{$refund->order->customerAddress}}</div>
                                                <!--end::Text-->

                                                <!--end::Description-->
                                                <!-- <div class="fw-semibold fs-7 text-gray-600">
                                                    Phone: {{$refund->order->customerPhone}}<br>
                                                    Livonia, MI 48150
                                                </div> -->
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->

                                        <!--begin::Row-->
                                        <div class="row g-5 mb-11">
                                            <!--end::Col-->
                                            <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-semibold fs-7 text-gray-600 mb-1">Dropshipper ID:</div>
                                                <!--end::Label-->

                                                <!--end::Col-->
                                                <div class="fw-bold fs-6 text-gray-800">
                                                    <a href="{{route('dropshipper.show', $refund->order->dropshipper->id)}}" target="_blank">{{$refund->order->dropshipper->id}}</a>

                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Col-->

                                        </div>
                                        <!--end::Row-->

                                        <!--begin::Content-->
                                        <div class="flex-grow-1">
                                            <!--begin::Table-->
                                            <div class="table-responsive border-bottom mb-9">
                                                <table class="table mb-3">
                                                    <thead>
                                                        <tr class="border-bottom fs-6 fw-bold text-muted">
                                                            <th class="min-w-175px pb-2">Item</th>
                                                            <th class="min-w-70px pb-2">Quantity</th>
                                                            <th class="min-w-140px pb-2">Total Price</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($refund->orderRefundItems as $item)
                                                        <tr class="fw-bold text-gray-700 fs-5">
                                                            <td class="">
                                                                {{ $item->orderItem->product->name->value }}
                                                            </td>

                                                            <td class="pt-6"> {{$item->orderItem->quantity }}</td>
                                                            <td class="pt-6"> {{$item->orderItem->totalPrice }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--end::Table-->

                                            

                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Invoice 2 content-->
                            </div>
                            <!--end::Content-->

                            
                            <!--begin::Sidebar-->
                            <div class="m-0">
                                <!--begin::Invoice 2 sidebar-->
                                <div
                                    class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
                                    <!--begin::Labels-->
                                    <div class="mb-8">
                                        @if($refund->status_id !== 6)
                                        <span class="badge badge-light-success me-2">Approved</span>
                                        @endif
                                        <span class="badge badge-light-warning">{{getStatusTitle($refund->status_id)}}</span>
                                    </div>
                                    <!--end::Labels-->
                                    @if($refund->status_id !== 6 && $refund->status_id !== 7)
                                    <!--begin::Title-->
                                    <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">Shipment DETAILS</h6>
                                    <!--end::Title-->

                                    <!--begin::Item-->
                                    <div class="mb-6">
                                        <div class="fw-semibold text-gray-600 fs-7">Aymakan tracking number:</div>

                                        <div class="fw-bold text-gray-800 fs-6">{{ $refund->tracking_number }}</div>
                                    </div>
                                    <!--end::Item-->
                                    @if($refund->pdf_label)
                                    <!--begin::Item-->
                                    <div class="mb-6">
                                        <div class="fw-semibold text-gray-600 fs-7">Aymakan delivery certificate</div>

                                        <div class="fw-bold fs-6 text-gray-800">
                                            you can view it from

                                            <a href="{{$refund->pdf_label}}" target="blank" class="link-primary ps-1">This link</a>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                    @endif
                                    
                                    @endif
                                    
                                    <!--begin::Title-->
                                    <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">Status history</h6>
                                    <!--end::Title-->

                                    @foreach($refund->orderRefund as $status)
                                    <!--begin::Item-->
                                    <div class="m-0">
                                        <div class="fw-semibold text-gray-600 fs-7">{{getStatusTitle($status->status_id)}}</div>

                                        <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center">
                                            Updated At

                                            <span class="fs-7 text-success d-flex align-items-center">
                                                <span class="bullet bullet-dot bg-success mx-2"></span>

                                                {{$status->created_at->format('Y-m-d H:i')}}
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                    @endforeach
                                </div>
                                <!--end::Invoice 2 sidebar-->
                            </div>
                            <!--end::Sidebar--> 
                        </div>
                        
                    </div>
                    <!--end::Body-->
                </div>

                <!--begin::Messenger-->
                <div class="card mt-4" id="kt_chat_messenger">
                    <!--begin::Card header-->
                    <div class="card-header" id="kt_chat_messenger_header">
                        <!--begin::Title-->
                        <div class="card-title">
                                            <!--begin::User-->
                                <div class="d-flex justify-content-center flex-column me-3">
                                    <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{$refund->Order->dropshipper->first_name ?? $refund->Order->dropshipper->email}}</a>
                                </div>
                                <!--end::User-->
                        </div>
                        <!--end::Title-->

                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body" id="kt_chat_messenger_body">
                        <!--begin::Messages-->
                        <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_app_toolbar, #kt_toolbar, #kt_footer, #kt_app_footer, #kt_chat_messenger_header, #kt_chat_messenger_footer" data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_messenger_body" data-kt-scroll-offset="5px" style="max-height: 198px;">

                            <!--begin::Message(in)-->
                            @foreach($refund->refundMessages as $refundMessage)
                            @if(user()->id === $refundMessage->messagable_id && user()->accountType() == $refundMessage->messagable_type)
                            <!--begin::Message(out)-->
                            <div class="d-flex justify-content-end mb-10 ">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column align-items-end">
                                    <!--begin::User-->
                                    <div class="d-flex align-items-center mb-2">
                                                            <!--begin::Details-->
                                            <div class="me-3">
                                                <span class="text-muted fs-7 mb-1">{{ $refundMessage->created_at->diffForHumans() }}</span>
                                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>  
                                            </div>
                                            <!--end::Details-->

                                            <!--begin::Avatar-->
                                            <!-- <div class="symbol  symbol-35px symbol-circle ">
                                                <img alt="Pic" src="/metronic8/demo31/assets/media/avatars/300-1.jpg">
                                            </div> -->
                                            <!--end::Avatar-->                 
                                    </div>
                                    <!--end::User-->

                                    <!--begin::Text-->
                                    <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text">
                                        
                                    {{ $refundMessage->message }}
                                    </div>
                                    <!--end::Text-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Message(out)-->
                            
                            @else
                            <div class="d-flex justify-content-start mb-10 ">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column align-items-start">
                                    <!--begin::User-->
                                    <div class="d-flex align-items-center mb-2">
                                            <!--begin::Details-->
                                            <div class="ms-3">
                                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">{{ $refund->Order->dropshipper->first_name ?? $refund->Order->dropshipper->email }}</a>
                                                <span class="text-muted fs-7 mb-1">{{ $refundMessage->created_at->diffForHumans()}}</span>
                                            </div>
                                            <!--end::Details-->
                                        
                                    </div>
                                    <!--end::User-->

                                    <!--begin::Text-->
                                    <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">
                                    {{ $refundMessage->message }}    
                                    </div>
                                    <!--end::Text-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Message(in)-->
                            @endif

                            @endforeach
                         
                        </div>
                        <!--end::Messages-->
                    </div>
                    <!--end::Card body-->

                    <form action="{{route('order.sendMessage', $refund)}}" method="POST" class="card-footer pt-4" id="kt_chat_messenger_footer">
                        @csrf
                        <!--begin::Input-->
                        <textarea name="message" class="form-control form-control-flush mb-3" rows="1" data-kt-element="input" placeholder="Type a message">            
                        </textarea>
                        <!--end::Input-->

                        <!--begin:Toolbar-->
                        <div class="d-flex flex-stack">
                            <!--begin::Send-->
                            <button type="submit" class="btn btn-primary" type="button" data-kt-element="send">Send</button>
                            <!--end::Send-->
                        </div>
                        <!--end::Toolbar-->
                    </form>
                </div>
                <!--end::Messenger-->    
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

</div>
<!--end:::Main-->

@endsection

@section('second-sidebar')
@include('order::layouts.sidebar')
@endsection

@push('scripts')
<script>
    
</script>
@endpush