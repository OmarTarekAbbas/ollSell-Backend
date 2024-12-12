<div class="row">
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderBulkCount != 0)
                            {{number_format(($totalOrderBulkCount/$totalOrderCount)* 100)  }}%
                            </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderEasyOrderCount != 0)
                            {{number_format(($totalOrderEasyOrderCount/$totalOrderCount)* 100)  }}%
                            </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->    <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderManualCount != 0)
                            {{number_format(($totalOrderManualCount/$totalOrderCount)* 100)  }}%
                            </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderOllopsCount + $totalOrderAssginCount+ $totalOrderPrepaidCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{($totalOrderOllopsCount+$totalOrderAssginCount+$totalOrderPrepaidCount) ? number_format((($totalOrderOllopsCount+$totalOrderAssginCount+$totalOrderPrepaidCount)/$totalOrderCount)*100) : 0}}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Validation</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkAssginCount + $totalOrderBulkOllopsCount+$totalOrderBulkPrepaidCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{($totalOrderBulkAssginCount+$totalOrderBulkOllopsCount+$totalOrderBulkPrepaidCount) ? number_format((($totalOrderBulkAssginCount+$totalOrderBulkPrepaidCount+$totalOrderBulkOllopsCount)/$totalOrderBulkCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Validation</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderOllopsCount+$totalOrderEasyOrderPrepaidCount+$totalOrderEasyOrderAssginCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>

                                {{($totalOrderEasyOrderOllopsCount+$totalOrderEasyOrderAssginCount+$totalOrderEasyOrderPrepaidCount) ? number_format((($totalOrderEasyOrderOllopsCount+$totalOrderEasyOrderPrepaidCount+$totalOrderEasyOrderAssginCount)/$totalOrderEasyOrderCount)* 100) :0 }}%

                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Validation</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualOllopsCount+$totalOrderManualAssginCount+$totalOrderManualPrepaidCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>

                                {{($totalOrderManualOllopsCount+$totalOrderManualAssginCount+$totalOrderManualPrepaidCount) ? number_format((($totalOrderManualOllopsCount+$totalOrderManualAssginCount+$totalOrderManualPrepaidCount)/$totalOrderManualCount)* 100)  : 0}}%

                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Validation</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderOllopsCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderOllopsCount != 0)
                            {{number_format(($totalOrderOllopsCount/$totalOrderCount)*100)}}%
                            @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Validation by Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkOllopsCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderBulkOllopsCount != 0)
                            {{number_format(($totalOrderBulkOllopsCount/$totalOrderBulkCount)* 100)  }}%
                                @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Validation by Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderOllopsCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderEasyOrderOllopsCount != 0)
                            {{number_format(($totalOrderEasyOrderOllopsCount/$totalOrderEasyOrderCount)* 100)  }}%
                            @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Validation by Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualOllopsCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderManualOllopsCount != 0)
                            {{number_format(($totalOrderManualOllopsCount/$totalOrderManualCount)* 100)  }}%
                                @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Validation by Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderAssginCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderAssginCount != 0)
                            {{number_format(($totalOrderAssginCount/$totalOrderCount)* 100)  }}%
                                @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Validation by System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkAssginCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderBulkAssginCount != 0)
                            {{number_format(($totalOrderBulkAssginCount/$totalOrderBulkCount)* 100)  }}%
                            @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Validation by System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderAssginCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderEasyOrderAssginCount != 0)
                            {{number_format(($totalOrderEasyOrderAssginCount/$totalOrderEasyOrderCount)* 100)  }}%
                                @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Validation by System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualAssginCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderManualAssginCount != 0)
                            {{number_format(($totalOrderManualAssginCount/$totalOrderManualCount)* 100)  }}%
                                @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Validation by System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderPrepaidCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderPrepaidCount != 0)
                                {{number_format(($totalOrderPrepaidCount/$totalOrderCount)* 100)  }}%
                            @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Validation Prepaid</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkPrepaidCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderBulkAssginCount != 0)
                                {{number_format(($totalOrderBulkAssginCount/$totalOrderBulkCount)* 100)  }}%
                            @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Validation by Prepaid</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderPrepaidCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderEasyOrderPrepaidCount != 0)
                                {{number_format(($totalOrderEasyOrderPrepaidCount/$totalOrderEasyOrderCount)* 100)  }}%
                            @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Validation by Prepaid</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualPrepaidCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($totalOrderManualPrepaidCount != 0)
                                {{number_format(($totalOrderManualPrepaidCount/$totalOrderManualCount)* 100)  }}%
                            @endif
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Validation by Prepaid</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Pending</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Pending</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Pending</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Pending</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderOllopsPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Pending Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkOllopsPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Pending Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderOllopsPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Pending Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualOllopsPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Pending Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderOperatorPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Pending System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkOperatorPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Pending System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderOperatorPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Pending System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualOperatorPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Pending System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderOllopsOperaterPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Ollops Pending And Assign Operator</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkOllopsOperaterPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Ollops And Pending Assign Operator</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderOllopsOperaterPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Ollops And Pending Assign Operator</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualOllopsOperaterPendingCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Ollops And Pending Assign Operator</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderOperatorPendingWaitCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Ollops End Attemps Pending Assign Operator</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkOperatorPendingWaitCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Ollops End Attemps Pending Assign Operator</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderOperatorPendingWaitCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Ollops End Attemps Pending Assign Operator</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualOperatorPendingWaitCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Ollops End Attemps Pending Assign Operator</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderOllopsCancalledCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Cancalled Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkOllopsCancalledCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Cancalled Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderOllopsCancalledCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Cancalled Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualOllopsCancalledCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Cancalled Ollops</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderOperatorCancalledCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Cancalled System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderBulkOperatorCancalledCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Bulk Cancalled System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderEasyOrderOperatorCancalledCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Easy Order Cancalled System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($totalOrderManualOperatorCancalledCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Manual Cancalled System</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
</div>
<script>
    var fromDate = "{{ $currentPeriod['from'] }}";
    var toDate = "{{ $currentPeriod['to'] }}";
</script>