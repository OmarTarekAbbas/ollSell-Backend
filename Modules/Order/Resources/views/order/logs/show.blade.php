@extends($layout)

@section('title', 'Order Details')

@push('styles')
@endpush
@section('content')

    <div class="card">
        <!--begin::Card head-->
        <div class="card-header card-header-stretch">
            <!--begin::Title-->
            <div class="card-title d-flex align-items-center">
                <i class="ki-outline ki-calendar-8 fs-1 text-primary me-3 lh-0"></i>

                <h3 class="fw-bold m-0 text-gray-800">Order #{{$order->id}} Logs</h3>
            </div>
            <!--end::Title-->

            <div class="card-toolbar m-0">
                <div>
                    <a href="{{route('order.show', $order)}}" class="btn btn-success mt-4">Go to order</a>
                </div>
            </div>

        </div>
        <!--end::Card head-->

        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Tab Content-->
            <div class="tab-content">
                <!--begin::Tab panel-->
                <div id="kt_activity_today" class="card-body p-0 tab-pane fade show active"
                     style="background-color:transparent !important">
                    <!--begin::Timeline-->
                    <div class="timeline timeline-border-dashed">
                        @forelse ($logs as $log)
                            <div class="timeline-item">
                                <div class="timeline-line"></div>
                                <div class="timeline-icon">
                                    <i class="ki-outline ki-message-text-2 fs-2 text-gray-500"></i>
                                </div>
                                <div class="timeline-content mb-10 mt-n1">
                                    <div class="pe-3 mb-5">
                                        <div class="fs-5 fw-semibold mb-2">
                                            {{ $log->log_text }}
                                        </div>
                                        <div class="d-flex align-items-center mt-1 fs-6">
                                            <div class="text-muted me-2 fs-7">Performed at
                                                {{ $log->created_at->format('Y-m-d h:i A') }} </div>
                                            <div>
                                                @if($log->user_id)
                                                    @if($log->user_type == 'App\Models\User')
                                                        <a style="color:#06a0f7 !important"
                                                           href="{{route('user.show', $log->user_id)}}">
                                                            by {{ $log->user_type::find($log->user_id)->name }}
                                                            (ID: {{ $log->user_id }})
                                                        </a>
                                                    @else
                                                        <a style="color:#06a0f7 !important"
                                                           href="{{route('dropshipper.show', $log->user_id)}}">
                                                            by {{ $log->user_type::find($log->user_id)->name }}
                                                            (ID: {{ $log->user_id }})
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h4 class="text-center">
                                No Changes on status/substatus or remarks yet.
                            </h4>
                        @endforelse
                    </div>
                    <!--end::Timeline-->
                </div>
                <!--end::Tab panel-->

            </div>
            <!--end::Tab Content-->
        </div>
        <!--end::Card body-->
    </div>

@endsection

@section('second-sidebar')
    @include('order::layouts.sidebar')
@endsection

@push('scripts')
@endpush
