<div class="row">
    <div class="col-xl-6"  >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12" style="background-color: #9c9999;">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <div class="col-6">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($total['total']['total'])}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        </div>
                        <!--end::Badge-->
                        <div class="col-6">
                            last
                            @if($totalLast['total']['total'] < 0)
                                <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$totalLast['total']['total']}}%
                            </span>
                            @elseif($totalLast['total']['total'] == 0)
                                <span class="badge badge-light-danger fs-base">
                            {{$totalLast['total']['total']}}%
                            </span>
                            @else
                                <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$totalLast['total']['total']}}%
                            </span>
                            @endif
                        </div>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-black-50 pt-1 fw-semibold fs-6">Total</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-6"  >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12" style="background-color: #9c9999;">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                    <div class="col-6">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($total['total']['canceled'])}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->

                    </div>
                        <div class="col-6">
                    @php
                        $percentage = number_format($total['total']['canceled'] ? $total['total']['canceled'] / $total['total']['total'] * 100 : 0,2);
                    @endphp
                    @if($percentage < 0)
                        <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$percentage}}%
                            </span>
                    @elseif($percentage == 0)
                        <span class="badge badge-light-danger fs-base">
                            {{$percentage}}%
                            </span>
                    @else
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$percentage}}%
                            </span>
                    @endif
                    </div>
                        <div class="col-6">
                            last
                    @if($totalLast['total']['canceled'] < 0)
                        <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$totalLast['total']['canceled']}}%
                            </span>
                    @elseif($totalLast['total']['canceled'] == 0)
                        <span class="badge badge-light-danger fs-base">
                            {{$totalLast['total']['canceled']}}%
                            </span>
                    @else
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$totalLast['total']['canceled']}}%
                            </span>
                    @endif
                    </div>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-black-50 pt-1 fw-semibold fs-6">Total Canceled</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-6" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12" style="background-color: #9c9999;">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <div class="col-12">
                            <!--begin::Amount-->
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($total['total']['qcanelled'])}}</span>
                            <!--end::Amount-->
                            <!--begin::Badge-->
                            <!--end::Badge-->

                        </div>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-black-50 pt-1 fw-semibold fs-6">Total Quick Canceled</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-6"  >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12" style="background-color: #9c9999;">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <div class="col-6">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($total['total']['pending'])}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        </div>
                        <div class="col-6">
                        @php
                            $percentage = number_format($total['total']['pending'] ? $total['total']['pending'] / $total['total']['total'] * 100 : 0,2);
                        @endphp
                        @if($percentage < 0)
                            <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$percentage}}%
                            </span>
                        @elseif($percentage == 0)
                            <span class="badge badge-light-danger fs-base">
                            {{$percentage}}%
                            </span>
                        @else
                            <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$percentage}}%
                            </span>
                        @endif
                        </div>
                        <div class="col-6">
                            last
                        @if($totalLast['total']['pending'] < 0)
                            <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$totalLast['total']['pending']}}%
                            </span>
                        @elseif($totalLast['total']['pending'] == 0)
                            <span class="badge badge-light-danger fs-base">
                            {{$totalLast['total']['pending']}}%
                            </span>
                        @else
                            <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$totalLast['total']['pending']}}%
                            </span>
                        @endif
                    </div>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-black-50 pt-1 fw-semibold fs-6">Total pending</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-6"  >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12" style="background-color: #9c9999;">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->

                    <div class="col-6">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($total['total']['validation'])}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                        <div class="col-6">
                        @php
                            $percentage = number_format($total['total']['validation'] ? $total['total']['validation'] / $total['total']['total'] * 100 : 0,2);
                        @endphp
                        @if($percentage < 0)
                            <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$percentage}}%
                            </span>
                        @elseif($percentage == 0)
                            <span class="badge badge-light-danger fs-base">
                            {{$percentage}}%
                            </span>
                        @else
                            <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$percentage}}%
                            </span>
                        @endif
                    </div>
                        <div class="col-6">
                            last
                        @if($totalLast['total']['validation'] < 0)
                            <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$totalLast['total']['validation']}}%
                            </span>
                        @elseif($totalLast['total']['validation'] == 0)
                            <span class="badge badge-light-danger fs-base">
                            {{$totalLast['total']['validation']}}%
                            </span>
                        @else
                            <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$totalLast['total']['validation']}}%
                            </span>
                        @endif
                        </div>

                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-black-50 pt-1 fw-semibold fs-6">Total validation</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    @foreach($total['total']['remark'] ?? [] as $name => $count)
        <div class="col-xl-6" >
            <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
                <!--begin: Statistics Widget 6-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <div class="card-title d-flex flex-column">
                        <!--begin::Info-->
                        <div class="d-flex align-items-center">

                        <div class="col-6">
                            <!--begin::Amount-->
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($count)}}</span>
                            <!--end::Amount-->
                            <!--begin::Badge-->
                            <!--end::Badge-->
                        </div>
                            <div class="col-6">
                            @php
                                $percentage = number_format($total['total']['remark'][$name] ? $total['total']['remark'][$name] / $total['total']['canceled'] * 100 : 0,2);
                            @endphp
                            @if($percentage < 0)
                                <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$percentage}}%
                            </span>
                            @elseif($percentage == 0)
                                <span class="badge badge-light-danger fs-base">
                            {{$percentage}}%
                            </span>
                            @else
                                <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$percentage}}%
                            </span>
                            @endif
                            </div>
                            <div class="col-6">
                                last
                            @if($totalLast['total']['remark'][$name] < 0)
                                <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$totalLast['total']['remark'][$name]}}%
                            </span>
                            @elseif($totalLast['total']['remark'][$name] == 0)
                                <span class="badge badge-light-danger fs-base">
                            {{$totalLast['total']['remark'][$name]}}%
                            </span>
                            @else
                                <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$totalLast['total']['remark'][$name]}}%
                            </span>
                            @endif
                        </div>
                        </div>
                        <!--end::Info-->
                        <!--begin::Subtitle-->
                        <span class="text-black-50 pt-1 fw-semibold fs-6">{{$name}}</span>
                        <!--end::Subtitle-->
                    </div>
                    <!--end::Title-->
                </div>
                <!--end: Statistics Widget 6-->
            </div>
        </div>
    @endforeach
</div>
<div class="row">
    <!--begin::Col-->

    <!--begin::Card widget 5-->
    <div class="card card-flush">
        <!--begin::Header-->
        <div class="card-header">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-black-50 pt-1 fw-semibold fs-6">Remarks of Orders</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end::Header-->
        <!--begin::Card body-->
        <div class="card-body 4 d-flex align-items-center">
            @include('report::report.admin.performance.cancelled.charts.remark', ['data' => $chart,'id'=>'chart1'])
            <!--end::Labels-->
        </div>
        @if(count($notes))
            <div class="card-body 4 d-flex align-items-center">
                @foreach($notes as $note)
                    {{$note}}<br>
                @endforeach

                <!--end::Labels-->
            </div>
        @endif
        <!--end::Card body-->
    </div>
</div>
<script>
    var fromDate = "{{ $currentPeriod['from'] }}";
    var toDate = "{{ $currentPeriod['to'] }}";
</script>