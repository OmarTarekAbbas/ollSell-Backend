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
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Source Platform of Orders</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end::Header-->
        <!--begin::Card body-->
        <div class="card-body 4 d-flex align-items-center">
            @include('report::report.admin.performance.platform.charts.platform', ['data' => $source_chart,'id'=>'chart1'])
            <!--end::Labels-->
        </div>
        <!--end::Card body-->
    </div>
</div>
<br><br>
<div class="row">

    <!--begin::Card widget 5-->
    <div class="card card-flush">
        <!--begin::Header-->
        <div class="card-header">
            <!--begin::Title-->
            <div class="card-title d-flex flex-column">
                <!--begin::Info-->
                <!--end::Info-->
                <!--begin::Subtitle-->
                <span class="text-gray-400 pt-1 fw-semibold fs-6">Created Platform of Orders</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Title-->
        </div>
        <!--end::Header-->
        <!--begin::Card body-->
        <div class="card-body 4 d-flex align-items-center">
            @include('report::report.admin.performance.platform.charts.platform', ['data' => $created_chart,'id'=>'chart2'])
            <!--end::Labels-->
        </div>
        <!--end::Card body-->
    </div>

</div>