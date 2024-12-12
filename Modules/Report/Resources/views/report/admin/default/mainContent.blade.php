<div class="row">
    <div class="col-xl-6">
        <!--begin: Statistics Widget 6-->
        <div class="card shadow-sm  card-xl-stretch mb-xl-12">
            <!--begin::Body-->
            <div class="card-body my-3">
                <canvas id="myChart" width="100%"></canvas>
            </div>
            <!--end:: Body-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    <div class="col-xl-6">
        <!--begin: Statistics Widget 6-->
        <div class="card  shadow-sm card-xl-stretch mb-xl-12">
            <div class="card-header">
                <h3 class="card-title">Top Performance</h3>
            </div>
            <!--begin::Body-->
            <div class="card-body my-3">
                <ul class="list-group">
                    @foreach ($topUsers as $topUser)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $topUser->email }}
                            <span class="badge badge-primary badge-pill">{{ $topUser->order_count }}</span>
                        </li>
                    @endforeach

                </ul>
            </div>
            <!--end:: Body-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    <div class="col-xl-6">
        <!--begin: Statistics Widget 6-->
        <div class="card shadow-sm  card-xl-stretch mb-xl-12">
            <!--begin::Body-->
            <div class="card-body my-3">
                <div id="kt_docs_google_chart_categories"></div>
            </div>
            <!--end:: Body-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
    <div class="col-xl-6">
        <!--begin: Statistics Widget 6-->
        <div class="card shadow-sm  card-xl-stretch mb-xl-12">
            <!--begin::Body-->
            <div class="card-body my-3">
                <div id="kt_docs_google_chart_products"></div>
            </div>
            <!--end:: Body-->
        </div>
        <!--end: Statistics Widget 6-->
    </div>
</div>





<script>
    // GOOGLE CHARTS INIT
    google.load('visualization', '1', {
        packages: ['corechart', 'bar', 'line']
    });
    var TopCategories = <?php echo json_encode($TopCategories); ?>;

    var arrcategory = [
        ['', ''],
    ];

    for (let i = 0; i < TopCategories.length; i++) {
         console.log(TopCategories[i].total);
        arrcategory.push([TopCategories[i].name + "  count " + TopCategories[i].total, TopCategories[i].total * 24]);
    }

    var TopProducts = <?php echo json_encode($TopProducts); ?>;

    var arr = [
        ['', ''],
    ];

    for (let i = 0; i < TopProducts.length; i++) {
        //  console.log(TopProducts[i].total);
        arr.push([TopProducts[i].name + "  count " + TopProducts[i].total, TopProducts[i].total * 24]);
    }

    google.setOnLoadCallback(function() {
        // LINE CHART
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'Day');
        data.addColumn('number', 'orders');


        data.addRows([
            [1, 37.8],
            [2, 30.9],
            [3, 25.4],
            [4, 11.7],
            [5, 11.9],
            [6, 8.8],
            [7, 7.6],
            [8, 12.3],
            [9, 16.9],
            [10, 12.8],
            [11, 5.3],
            [12, 6.6],
            [13, 4.8],
            [14, 4.2]

        ]);

        var options = {
            chart: {
                title: 'orders'
            },
            colors: ['#6e4ff5']
        };


        var optionscategory = {
            title: 'Categories',
            colors: ['#fe3995', '#f6aa33', '#6e4ff5']
        };
        var datacategory = google.visualization.arrayToDataTable(arrcategory);

        var chart2 = new google.visualization.PieChart(document.getElementById(
            'kt_docs_google_chart_categories'));
        chart2.draw(datacategory, optionscategory);

        var datapro = google.visualization.arrayToDataTable(arr);

        var optionspro = {
            title: 'products',
            colors: ['#fe3995', '#f6aa33', '#6e4ff5']
        };

        var chartpro = new google.visualization.PieChart(document.getElementById(
            'kt_docs_google_chart_products'));
        chartpro.draw(datapro, optionspro);
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($topOrders['points']) !!},
            datasets: [{
                label: '# Orders',
                data: {!! json_encode($topOrders['numbers']) !!},
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
