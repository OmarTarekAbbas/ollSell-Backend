@extends($layout)


@section('title', 'Deposit Request details')

@section('content')
<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::Order details page-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Order summary-->
                    <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                        <!--begin::Order details-->
                        <div class="card card-flush py-4 flex-row-fluid">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title w-100 d-flex justify-content-between">
                                    <h2>Deposit Request Details (#{{$data->id}})</h2>
                                    @permission('update_RedeemRequest')

                                    @if($data->status == 'pending')
                                    <div>
                                        <a href="{{ route('depositRequest.approved', $data) }}" target="blank" class="btn btn-success">Approve</a>
                                        <a href="{{ route('depositRequest.refused', $data) }}" target="blank" class="btn btn-danger">Reject</a>
                                    </div>
                                    @else
                                    <span class="badge badge-info">{{$data->status}}</span>
                                    @endif
                                    @endpermission

                                </div>
                            </div>
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                        <tbody class="fw-semibold text-gray-600">
                                            {{-- <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-profile-circle fs-2 me-2"></i>Customer</div>
                                                </td>
                                                <td class="fw-bold text-end">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <!--begin:: Avatar -->
                                                        <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                                                            <a href="#">
                                                                <div class="symbol-label">
                                                                    <img src="{{asset('dashboard')}}/assets/media/avatars/300-23.jpg" alt="Dan Wilson" class="w-100" />
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <!--end::Avatar-->
                                                        <!--begin::Name-->
                                                        <a href="#" class="text-gray-600 text-hover-primary">{{ $data->customerName }}</a>
                                                        <!--end::Name-->
                                                    </div>
                                                </td>
                                            </tr> --}}
                                            @if($data->customerName)
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-sms fs-2 me-2"></i>Name</div>
                                                </td>
                                                <td>
                                                    {{ $data->customerName  }}
                                                </td>
                                            </tr>
                                            @endif
                                            
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-sms fs-2 me-2"></i>Email</div>
                                                </td>
                                                <td class="fw-bold text-end">
                                                    <a href="#" class="text-gray-600 text-hover-primary">{{$data->dropshipper->email}}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-phone fs-2 me-2"></i>Phone</div>
                                                </td>
                                                <td class="fw-bold text-end">{{$data->dropshipper->phone}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-phone fs-2 me-2"></i>Amount</div>
                                                </td>
                                                <td class="fw-bold text-end">{{$data->amount}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-sms fs-2 me-2"></i>Status</div>
                                                </td>
                                                <td class="fw-bold text-end">
                                                    <a href="#" class="text-gray-600 text-hover-primary">{{ $data->status }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">
                                                    <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-phone fs-2 me-2"></i>Image</div>
                                                </td>
                                                <td class="fw-bold text-end">
                                                    <img style="width:400px;" src="{{ getFile($data->avatar->file??null,pathType()['ip'],getFileNameServer($data->avatar))  }}"/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Order details-->
                        
                    </div>
                    <!--end::Order summary-->
                   
                </div>
                <!--end::Order details page-->
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
        var chart = am4core.create("kt_maps_widget_2_maps", am4maps.MapChart);
        // Set map definition
        chart.geodata = am4geodata_worldLow;
        // Set projection
        chart.projection = new am4maps.projections.Miller();
        // Create map polygon series
        var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
        // Make map load polygon (like country names) data from GeoJSON
        polygonSeries.useGeodata = true;
        // Configure series
        var polygonTemplate = polygonSeries.mapPolygons.template;
        polygonTemplate.tooltipText = "{name}";
        polygonTemplate.fill = am4core.color("#74B266");
        // Create hover state and set alternative fill color
        var hs = polygonTemplate.states.create("hover");
        hs.properties.fill = am4core.color("#367B25");
        // Remove Antarctica
        polygonSeries.exclude = ["AQ"];
        var fromPHP = @json($data->target_market);
        var test = [];
        var zoomTo = [];
        for (var i = 0; i < fromPHP.length; i++) {
            test[i] = {
                'id': (fromPHP[i].code).toUpperCase(),
                'name': fromPHP[i].name_en,
                'value': 100,
                "fill": am4core.color("#F05C5C")
            }
            console.log(fromPHP[i].name_en)
            zoomTo.push((fromPHP[i].code).toUpperCase());
        }
        // Add some data
        polygonSeries.data = test;
        chart.events.on("ready", function (ev) {
            // Init extremes
            var north, south, west, east;

            // Find extreme coordinates for all pre-zoom countries
            for (var i = 0; i < zoomTo.length; i++) {
                var country = polygonSeries.getPolygonById(zoomTo[i]);
                if (north == undefined || (country.north > north)) {
                    north = country.north;
                }
                if (south == undefined || (country.south < south)) {
                    south = country.south;
                }
                if (west == undefined || (country.west < west)) {
                    west = country.west;
                }
                if (east == undefined || (country.east > east)) {
                    east = country.east;
                }
                country.isActive = true
            }

            // Pre-zoom
            chart.zoomToRectangle(north, east, south, west, 1, true);
        });
        // Bind "fill" property to "fill" key in data
        polygonTemplate.propertyFields.fill = "fill";
    </script>
@endpush
