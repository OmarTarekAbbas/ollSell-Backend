<div class="row">

    <div class="col-xl-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodPay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderCodPay ? number_format(($orderCodPay/$orderCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Paid Cod</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderWalletPay + $orderOnlinePay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{($orderWalletPay+$orderOnlinePay) ? number_format((($orderWalletPay+$orderOnlinePay)/$orderCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Paid Online</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>


    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCount)}}</span>
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
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($orderCodCount != 0)
                                {{number_format(($orderCodCount/$orderCount)* 100)  }}%
                            </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Cod</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderWalletCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($orderWalletCount != 0)
                                {{number_format(($orderWalletCount/$orderCount)* 100)  }}%
                            </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Wallet</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderOnlineCount)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge--> <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            @if($orderOnlineCount != 0)
                                {{number_format(($orderOnlineCount/$orderCount)* 100)  }}%
                            </span>
                        @endif
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Online</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodWaitPay + $orderWalletWaitPay+ $orderOnlineWaitPay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{($orderCodWaitPay + $orderWalletWaitPay+ $orderOnlineWaitPay) ? number_format((($orderCodWaitPay + $orderWalletWaitPay+ $orderOnlineWaitPay)/$orderCount)*100) : 0}}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Pending Payment</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodWaitPay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderCodWaitPay ? number_format(($orderCodWaitPay/$orderCodCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Pending Payment Cod</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderWalletWaitPay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderWalletWaitPay ? number_format(($orderWalletWaitPay/$orderWalletCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Pending Payment Wallet</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderOnlineWaitPay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderOnlineWaitPay ? number_format(($orderOnlineWaitPay/$orderOnlineCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Pending Payment Online</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodPay + $orderWalletPay+ $orderOnlinePay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{($orderCodPay + $orderWalletPay+ $orderOnlinePay) ? number_format((($orderCodPay + $orderWalletPay+ $orderOnlinePay)/$orderCount)*100) : 0}}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Paid </span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodPay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderCodPay ? number_format(($orderCodPay/$orderCodCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Paid  Cod</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderWalletPay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderWalletPay ? number_format(($orderWalletPay/$orderWalletCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Paid  Wallet</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderOnlinePay)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderOnlinePay ? number_format(($orderOnlinePay/$orderOnlineCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders Paid  Online</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodCanceled + $orderWalletCanceled+ $orderOnlineCanceled)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{($orderCodCanceled + $orderWalletCanceled+ $orderOnlineCanceled) ? number_format((($orderCodCanceled + $orderWalletCanceled+ $orderOnlineCanceled)/$orderCount)*100) : 0}}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders  Canceled</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodCanceled)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderCodCanceled ? number_format(($orderCodCanceled/$orderCodCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders  Canceled Cod</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderWalletCanceled)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderWalletCanceled ? number_format(($orderWalletCanceled/$orderWalletCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders  Canceled Wallet</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderOnlineCanceled)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderOnlineCanceled ? number_format(($orderOnlineCanceled/$orderOnlineCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders  Canceled Online</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodRejected + $orderWalletRejected+ $orderOnlineRejected)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{($orderCodRejected + $orderWalletRejected+ $orderOnlineRejected) ? number_format((($orderCodRejected + $orderWalletRejected+ $orderOnlineRejected)/$orderCount)*100) : 0}}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders  Rejected</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderCodRejected)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderCodRejected ? number_format(($orderCodRejected/$orderCodCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders  Rejected Cod</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderWalletRejected)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderWalletRejected ? number_format(($orderWalletRejected/$orderWalletCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders  Rejected Wallet</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($orderOnlineRejected)}}</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <!--end::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                                {{$orderOnlineRejected ? number_format(($orderOnlineRejected/$orderOnlineCount)* 100) :0 }}%
                            </span>
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Orders  Rejected Online</span>
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