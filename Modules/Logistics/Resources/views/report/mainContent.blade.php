<div class="row">
    <div class="col-xl-4" id="tableshowinout" style="display: none">
        <div class="card card-flush py-4 flex-row-fluid">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>
                        Delivering The specified time
                    </h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                        <tbody class="fw-semibold text-gray-600">
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                        Within SLA
                                    </div>
                                </td>
                                <td class="fw-bold text-end">
                                    <div class="d-flex align-items-center justify-content-end">
                                        {{ $orders['allWithin'] }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                        Outside SLA
                                    </div>
                                </td>
                                <td class="fw-bold text-end">
                                    {{ $orders['allOut'] }}
                                </td>
                            </tr>


                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
    <div class="col-xl-8">
        <!--begin: Statistics Widget 6-->
        <div class="card shadow-sm  card-xl-stretch">
            <!--begin::Body-->
            <div class="card-body my-3">
                <div id="donutchart" style="height: 500px"></div>
            </div>
            <!--end:: Body-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    <div class="col-xl-4" style="display: flex;flex-direction: column;justify-content: space-between;">
        <div class="col-xl-12" id="withinandout">
            <!--begin::Card widget 3-->
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                onclick="openmodel('exampleModalWITHOUT')"
                style="background-color: #CA3433;background-image:url('/metronic8/demo39/assets/media/svg/shapes/wave-bg-red.svg')">
                <!--begin::Header-->
                <div class="card-header pt-5 mb-3">
                    <!--begin::Icon-->
                    <div class="d-flex flex-center rounded-circle h-80px w-80px"
                        style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #CA3433">
                        <i class="ki-outline ki-call text-white fs-2qx lh-0"></i>
                    </div>
                    <!--end::Icon-->
                </div>
                <!--end::Header-->

                <!--begin::Card body-->
                <div class="card-body d-flex align-items-end mb-3">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <span class="fs-4hx text-white fw-bold me-6">{{ $orders['allOut'] }} </span>

                        <div class="fw-bold fs-5 text-white">
                            <span class="d-block">Outside </span>
                            <span class="">SLA</span>
                        </div>
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Card body-->


            </div>

        </div>
        <div class="col-xl-12" id="withoutandin">
            <!--begin::Card widget 3-->
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                style="background-color: #3EA055;background-image:url('/metronic8/demo39/assets/media/svg/shapes/wave-bg-purple.svg')">
                <!--begin::Header-->
                <div class="card-header pt-5 mb-3">
                    <!--begin::Icon-->
                    <div class="d-flex flex-center rounded-circle h-80px w-80px"
                        style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #3EA055">
                        <i class="ki-outline ki-call text-white fs-2qx lh-0"></i>
                    </div>
                    <!--end::Icon-->
                </div>
                <!--end::Header-->

                <!--begin::Card body-->
                <div class="card-body d-flex align-items-end mb-3">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <span class="fs-4hx text-white fw-bold me-6">{{ $orders['allWithin'] }}</span>

                        <div class="fw-bold fs-5 text-white">
                            <span class="d-block">Within </span>
                            <span class="">SLA</span>
                        </div>
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Card body-->
            </div>
        </div>
    </div>


</div>
<br>
<div class="row" id="printchart">
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm  card-xl-stretch mb-xl-12">
                <!--begin::Body-->
                <div class="card-body my-3">
                    <div id="time_chart_div" style=" height: 500px;"></div>
                </div>
                <!--end:: Body-->
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm  card-xl-stretch mb-xl-12">
                <!--begin::Body-->
                <div class="card-body my-3">
                    <div id="chart_div" style=" height: 500px;"></div>
                </div>
                <!--end:: Body-->
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalCities" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class=" modal-dialog modal-fullscreen p-9">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelCities">Orders Within SLA</h5>
                <button class="btn btn-success btn-sm " style=" margin-left: 999px;"
                onclick="exportOrdersTime()">
                <i class="fa fa-spinner fa-spin exportLoadingmoule"  style="display: none"></i>
                <i class="fa fa-file-export" ></i>
                Export
            </button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="periodexport" value="" />
            <input type="hidden" id="cityexport" value="" />
            <input type="hidden" id="supplierexport" value="" />
            <input type="hidden" id="statusIdexport" value="" />
            <input type="hidden" id="dropshipperexport" value="" />
            <input type="hidden" id="fromDateexport" value="" />
            <input type="hidden" id="toDateexport" value="" />
            <input type="hidden" id="typeexport" value="" />
            <input type="hidden" id="time_ordersexport" value="" />
            <input type="hidden" id="city_idexport" value="" />
            
            <div class="modal-body" id="orderscities">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    function exportOrders(orders, key) {
        $("#exportLoading" + key).show();

        var route = "{{ route('exportReporting') }}";

        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                orders: orders,

            },
            datatype: 'json',
            success: function(data) {
                const downloadUrl = data.url;
                // Create an anchor element
                const downloadLink = document.createElement("a");
                downloadLink.href = downloadUrl;
                downloadLink.target = "_blank"; // Open the link in a new tab
                downloadLink.setAttribute("download", ""); // Set the 'download' attribute to force download

                // Trigger a click event on the anchor element
                downloadLink.click();
                $("#exportLoading" + key).hide();


            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });



    };

    function exportOrdersTime() {
        //  this.exportLoading = true;
        $(".exportLoadingmoule").show();
        var fromDate = '';
        var toDate = '';
        if ($("#fromDateexport").val()) {
            fromDate = $("#fromDateexport").val();
        }
        if ($("#toDateexport").val()) {
            toDate = $("#toDateexport").val();
        }
        var route = "{{ route('exportCustamReporting') }}";
        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                period: $("#periodexport").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $(
                    "#periodexport").val(),
                city: $("#cityexport").val(),
                supplier: $("#supplierexport").val(),
                statusId: $("#statusIdexport").val(),
                dropshipper: $("#dropshipperexport").val(),
                fromDate: fromDate,
                toDate: toDate,
                type: $("#typeexport").val(),
                time_orders: $("#time_ordersexport").val(),
                city_id:$("#city_idexport").val(),
            },
            datatype: 'json',
            success: function(data) {
                const downloadUrl = data.url;
                // Create an anchor element
                const downloadLink = document.createElement("a");
                downloadLink.href = downloadUrl;
                downloadLink.target = "_blank"; // Open the link in a new tab
                downloadLink.setAttribute("download", ""); // Set the 'download' attribute to force download

                // Trigger a click event on the anchor element
                downloadLink.click();
                $(".exportLoadingmoule").hide();


            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });


    };



    google.charts.load('current', {
        'packages': ['corechart', 'bar']
    });
    google.charts.setOnLoadCallback(drawStuff);
    google.charts.setOnLoadCallback(drawTimeStuff);
    google.charts.setOnLoadCallback(drawChart);

    function drawTimeStuff() {
        var timeChartDiv = document.getElementById('time_chart_div')
        var data = google.visualization.arrayToDataTable([
            ['Date Range', 'Within SLA ', 'Outside SLA'],
            @if (@$request->period == 'today' || ($request->fromDate == $request->toDate && $request->period == 'thisCustom'))
                @foreach ($orders['points'] as $row)
                    ["{{ date_format($row['from'], 'H:i') }}:{{ date_format($row['to'], 'H:i') }}",
                        {{ $row['in'] }}, {{ $row['out'] }}
                    ],
                @endforeach
            @else
                @foreach ($orders['points'] as $row)
                    ["{{ date_format($row['from'], 'Y-m-d') }}:{{ date_format($row['to'], 'Y-m-d') }}",
                        {{ $row['in'] }}, {{ $row['out'] }}
                    ],
                @endforeach
            @endif

        ]);

        var materialOptions = {
            colors: ['#3EA055', "#CA3433"],
            width: 1200,
            chart: {
                title: 'Date Range',
                subtitle: 'The shipping company succeeded in delivering or not IN The specified time'
            },
            series: {
                0: {
                    axis: 'distance'
                }, // Bind series 0 to an axis named 'distance'.
                1: {
                    axis: 'brightness'
                } // Bind series 1 to an axis named 'brightness'.
            },
            axes: {
                y: {
                    distance: {
                        label: 'parsecs'
                    }, // Left y-axis.
                    brightness: {
                        side: 'right',
                        label: 'apparent magnitude'
                    } // Right y-axis.
                }
            }
        };

        var classicOptions = {
            width: 900,
            series: {
                0: {
                    targetAxisIndex: 0
                },
                1: {
                    targetAxisIndex: 1
                }
            },
            title: 'Nearby galaxies - distance on the left, brightness on the right',
            vAxes: {
                // Adds titles to each axis.
                0: {
                    title: 'parsecs'
                },
                1: {
                    title: 'apparent magnitude'
                }
            }
        };

        var materialChart = new google.charts.Bar(timeChartDiv);
        materialChart.draw(data, google.charts.Bar.convertOptions(materialOptions));
        google.visualization.events.addListener(materialChart, 'select', selectHandler);


        function selectHandler(e) {
            var selection = materialChart.getSelection();
            var fromDate = '';
            var toDate = '';
            if (selection.length > 0) {

                if ($("#fromDate").val()) {
                    fromDate = $("#fromDate").val();
                }
                if ($("#toDate").val()) {
                    toDate = $("#toDate").val();
                }
                // get column label
                var colLabel = data.getColumnLabel(selection[0].column);
                var time_orders = data.getValue(selection[0].row, 0);
                $('#orderscities').html(
                    '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                );
                $('#exampleModalCities').modal('toggle');
                $("#exampleModalLabelCities").text("Orders " + colLabel + ' (' + time_orders + ')');
                var route = "{{ route('orderTimes') }}";
                $.ajax({
                    url: route,
                    type: 'GET',
                    data: {
                        period: $("#period_type").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $(
                            "#period_type").val(),
                        city: $("#city").val(),
                        supplier: $("#supplier").val(),
                        statusId: $("#statusId").val(),
                        dropshipper: $("#dropshipper").val(),
                        fromDate: fromDate,
                        toDate: toDate,
                        type: colLabel,
                        time_orders: time_orders,
                    },
                    datatype: 'json',
                    success: function(data) {
                        $('#orderscities').html(data);
                        $("#periodexport").val($("#period_type").val());
                        $("#cityexport").val($("#city").val());
                        $("#supplierexport").val($("#supplier").val());
                        $("#statusIdexport").val($("#statusId").val());
                        $("#dropshipperexport").val($("#dropshipper").val());
                        $("#typeexport").val(colLabel);
                        $("#time_ordersexport").val(time_orders);
                        $("#fromDateexport").val(fromDate);
                        $("#toDateexport").val(toDate);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                    }
                });



                materialChart.setSelection([]);
            }
        }


    };

    function drawStuff() {
        var orders = [];
        @foreach ($orders['cities'] as $key => $row)
            orders["{{ $row['name'] }}"] = "{{ $key }}";
        @endforeach

        var chartDiv = document.getElementById('chart_div');

        var data = google.visualization.arrayToDataTable([
            ['cities', 'Within SLA ', 'Outside SLA'],
            @foreach ($orders['cities'] as $key => $row)
                ["{{ $row['name'] }}", {{ $row['in'] }}, {{ $row['out'] }}],
            @endforeach

        ]);

        var materialOptions = {
            colors: ['#3EA055', "#CA3433"],
            width: 1200,

            chart: {
                title: 'CITIES',
                subtitle: 'The shipping company succeeded in delivering or not IN The specified time'
            },
            series: {
                0: {
                    axis: 'distance'
                }, // Bind series 0 to an axis named 'distance'.
                1: {
                    axis: 'brightness'
                } // Bind series 1 to an axis named 'brightness'.
            },
            axes: {
                y: {
                    distance: {
                        label: 'parsecs'
                    }, // Left y-axis.
                    brightness: {
                        side: 'right',
                        label: 'apparent magnitude'
                    } // Right y-axis.
                }
            }
        };

        var classicOptions = {
            width: 900,
            series: {
                0: {
                    targetAxisIndex: 0
                },
                1: {
                    targetAxisIndex: 1
                }
            },
            title: 'Nearby galaxies - distance on the left, brightness on the right',
            vAxes: {
                // Adds titles to each axis.
                0: {
                    title: 'parsecs'
                },
                1: {
                    title: 'apparent magnitude'
                }
            }
        };
        var materialChart = new google.charts.Bar(chartDiv);
        materialChart.draw(data, google.charts.Bar.convertOptions(materialOptions));
        google.visualization.events.addListener(materialChart, 'select', selectHandler);


        // function selectHandler() {
        //     var selectedItem = materialChart.getSelection()[0];
        //     if (selectedItem) {
        //         var task = data.getValue(selectedItem.row, 0);
        //         alert('The user selected ' + task);
        //     }
        // }

        function selectHandler(e) {
            var selection = materialChart.getSelection();
            var fromDate = '';
            var toDate = '';
            if (selection.length > 0) {
                if ($("#fromDate").val()) {
                    fromDate = $("#fromDate").val();
                }
                if ($("#toDate").val()) {
                    toDate = $("#toDate").val();
                }
                // get column label
                var colLabel = data.getColumnLabel(selection[0].column);
                var city_name = data.getValue(selection[0].row, 0);
                $('#exampleModalCities').modal('toggle');
                $('#orderscities').html(
                    '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                );

                $("#exampleModalLabelCities").text("Orders " + colLabel + ' (' + city_name + ')');
                var route = "{{ route('orderCities') }}";
                $.ajax({
                    url: route,
                    type: 'GET',
                    data: {
                        period: $("#period_type").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $(
                            "#period_type").val(),
                        city: $("#city").val(),
                        supplier: $("#supplier").val(),
                        statusId: $("#statusId").val(),
                        dropshipper: $("#dropshipper").val(),
                        fromDate: fromDate,
                        toDate: toDate,
                        type: colLabel,
                        city_id: orders[city_name],
                    },
                    datatype: 'json',
                    success: function(data) {
                        $('#orderscities').html(data);

                        $("#periodexport").val($("#period_type").val());
                        $("#cityexport").val($("#city").val());
                        $("#supplierexport").val($("#supplier").val());
                        $("#statusIdexport").val($("#statusId").val());
                        $("#dropshipperexport").val($("#dropshipper").val());
                        $("#typeexport").val(colLabel);
                        $("#city_idexport").val(orders[city_name]);
                        $("#fromDateexport").val(fromDate);
                        $("#toDateexport").val(toDate);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                    }
                });



                materialChart.setSelection([]);
            }
        }
    };

    // function drawChart() {

    //     var data = google.visualization.arrayToDataTable([
    //         ['delivering', 'Number Orders'],
    //         ['With IN', {{ $orders['allWithin'] }}],
    //         ['Out', {{ $orders['allOut'] }}],

    //     ]);

    //     var options = {
    //         title: 'Delivering The specified time'
    //     };

    //     var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    //     chart.draw(data, options);
    // }


    function drawChart() {
        var fromDate,toDate;
        if ($("#fromDate").val()) {
               fromDate = $("#fromDate").val();
            }
            if ($("#toDate").val()) {
               toDate = $("#toDate").val();
            }
        var data = google.visualization.arrayToDataTable([
            ['delivering', 'Number Orders'],
            ['Within SLA', {{ $orders['allWithin'] }}],
            ['Outside SLA', {{ $orders['allOut'] }}],
        ]);

        var options = {
            title: 'Delivering The specified time',
            pieHole: 0.4,
            colors: ['#3EA055', "#CA3433"]
        };

        function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
                var task = data.getValue(selectedItem.row, 0);

                // if (task == 'Outside SLA') {
                //     $('#exampleModalWITHOUT').modal('toggle');
                // } else {
                //     $('#exampleModalWITHIN').modal('toggle');
                // }

                $('#exampleModalCities').modal('toggle');
                $('#orderscities').html(
                    '<div class="d-flex justify-content-center"><div style="height: 50px;width: 50px;margin: 50px;" class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div></div>'
                );

                $("#exampleModalLabelCities").text("Orders "+task);

                var route = "{{ route('orderAll') }}";
                $.ajax({
                    url: route,
                    type: 'GET',
                    data: {
                        period: $("#period_type").val() == null ? "{{ $request->period ?? 'thisMonth' }}" : $(
                            "#period_type").val(),
                        city: $("#city").val(),
                        supplier: $("#supplier").val(),
                        statusId: $("#statusId").val(),
                        dropshipper: $("#dropshipper").val(),
                        fromDate: fromDate,
                        toDate: toDate,
                        type: task,
                    },
                    datatype: 'json',
                    success: function(data) {
                        $('#orderscities').html(data);

                        $("#periodexport").val($("#period_type").val());
                        $("#supplierexport").val($("#supplier").val());
                        $("#statusIdexport").val($("#statusId").val());
                        $("#dropshipperexport").val($("#dropshipper").val());
                        $("#typeexport").val(task);
                        $("#fromDateexport").val(fromDate);
                        $("#toDateexport").val(toDate);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                    }
                });


            }
        }


        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        google.visualization.events.addListener(chart, 'select', selectHandler);

        chart.draw(data, options);
    }
</script>
