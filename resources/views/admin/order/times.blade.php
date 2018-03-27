@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Horários de Maior Requisição</div>

                <div class="panel-body">
                    <canvas id="myChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
    <script>
        var ctx = document.getElementById("myChart");
        var data = {{ json_encode($times) }};

        var default_colors = [
            'rgba(255, 99, 132,',
            'rgba(54, 162, 235,',
            'rgba(255, 206, 86,',
            'rgba(75, 192, 192,',
            'rgba(153, 102, 255,',
            'rgba(255, 159, 64,'
        ];

        var backgroundColors = Array();
        var borderColors = Array();

        data.forEach(function(element,index){
            backgroundColors.push(default_colors[index % default_colors.length] + '0.2)')
            borderColors.push(default_colors[index % default_colors.length] + '1)')
        });

        var labels = data.map(function(item,key){
           return (key < 10 ? "0" + key : key) + "h";
        });


        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Número de pedidos na hora',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    </script>
@stop
