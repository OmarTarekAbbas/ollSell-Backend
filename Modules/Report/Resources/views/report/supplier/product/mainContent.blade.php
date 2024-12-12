<div class="row">
    <div class="col-xl-3" onclick="getProduct()">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
        <!--begin: Statistics Widget 6-->
        <div class="card-header pt-5">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($currentSkuCount)}}</span>
                    <!--end::Amount-->
                    <!--begin::Badge-->
                    @if($percentageChangeSku < 0)
                        <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$percentageChangeSku}}%
                            </span>
                    @elseif($percentageChangeSku == 0)
                        <span class="badge badge-light-danger fs-base">
                            {{$percentageChangeSku}}%
                            </span>
                    @else
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$percentageChangeSku}}%
                            </span>
                    @endif
                    <!--end::Badge-->
                </div>
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Skus</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    </div>
    <div class="col-xl-3"  onclick="getLiveProduct(1)">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
        <!--begin: Statistics Widget 6-->
        <div class="card-header pt-5">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($activeCount)}}</span>
                    <!--end::Amount-->
                </div>
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Live Sku</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    </div>
    <div class="col-xl-3"  onclick="getLiveProduct(0)" >
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
        <!--begin: Statistics Widget 6-->
        <div class="card-header pt-5">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($InactiveCount)}}</span>
                    <!--end::Amount-->
                </div>
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Inactive Live Sku</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    </div>
    <div class="col-xl-3" >
        <div class="card  shadow-sm card-xl-stretch mb-xl-12">
        <!--begin: Statistics Widget 6-->
        <div class="card-header pt-5">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($productActiveOrder)}}</span>
                    <!--end::Amount-->
                </div>
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Active Sku Order</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    </div>
    <div class="col-xl-3" >
        <div class="card  shadow-sm card-xl-stretch mb-xl-12">
        <!--begin: Statistics Widget 6-->
        <div class="card-header pt-5">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($productInactiveOrder)}}</span>
                    <!--end::Amount-->
                </div>
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Inactive Sku Order</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    </div>
    <div class="col-xl-3" onclick="getWarehouse()">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
        <!--begin: Statistics Widget 6-->
        <div class="card-header pt-5">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($warehouseCount)}}</span>
                    <!--end::Amount-->
                </div>
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Warehouse</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    </div>
    <div class="col-xl-3">
        <div class="card  shadow-sm card-xl-stretch mb-xl-12">
        <!--begin: Statistics Widget 6-->
        <div class="card-header pt-5">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($dropshipperCount)}}</span>
                    <!--end::Amount-->
                </div>
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Dropshipper</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    </div>
    <div class="col-xl-3" onclick="getOrder(0)">
        <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
        <!--begin: Statistics Widget 6-->
        <div class="card-header pt-5">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($currentOrderCount)}}</span>
                    <!--end::Amount-->
                    <!--begin::Badge-->
                    @if($percentageChangeOrder < 0)
                        <span class="badge badge-light-danger fs-base">
                            <i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>
                            {{$percentageChangeOrder}}%
                            </span>
                    @elseif($percentageChangeOrder == 0)
                        <span class="badge badge-light-danger fs-base">
                            {{$percentageChangeOrder}}%
                            </span>
                    @else
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>
                            {{$percentageChangeOrder}}%
                            </span>
                    @endif
                    <!--end::Badge-->
                </div>
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Order</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    </div>
    <div class="col-xl-3" >
        <div class="card  shadow-sm card-xl-stretch mb-xl-12">
        <!--begin: Statistics Widget 6-->
        <div class="card-header pt-5">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($costOrder)}}</span>
                    <!--end::Amount-->
                </div>
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">cost Order</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    </div>
    @foreach($currentStatusOrder as $key => $value)
        <div class="col-xl-3" onclick="getOrder({{$key}})">
            <div class="card card-click shadow-sm card-xl-stretch mb-xl-12">
            <!--begin: Statistics Widget 6-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{number_format($value)}}</span>
                        <!--end::Amount-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">{{getStatusTitle($key)}}</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end: Statistics Widget 6-->
        </div>
        </div>
    @endforeach
</div>
<script>
  var  fromDate = "{{ $currentPeriod['from'] }}";
  var toDate = "{{ $currentPeriod['to'] }}";
</script>