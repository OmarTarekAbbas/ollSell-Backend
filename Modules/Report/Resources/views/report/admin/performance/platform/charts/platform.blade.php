<script type="text/javascript">
    var {{$id}} = <?php echo json_encode($data) ?>;
    google.charts.load("current", {packages:["corechart", 'bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(
                {{$id}}
        );


        var chart = new google.visualization.ColumnChart(document.getElementById('{{$id}}'));
        chart.draw(data);
    }
</script>
<div id="{{$id}}" style="width: 100%;height: 100%"></div>

