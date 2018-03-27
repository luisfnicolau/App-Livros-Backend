@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Lugares mais requisitados</div>

                <div class="panel-body">
                    <div id="map"></div>

                    <select id="map-mode" class="form-control">
                        <option>Heatmap</option>
                        <option>Markers</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('css')
    <style>
        #map{
            height: 400px;
        }
    </style>
@endsection

@section('js')
    <script>
        var map;
        var mode = "heatmap";

        $('#map-mode').change(function(){
            mode = $(this).val().toLowerCase();
            initMap();
        });

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {lat: -22.9116, lng: -43.1883},
                mapTypeId: 'roadmap'
            });

            addDataToMap();
        }

        function addDataToMap() {
            var results = {{ json_encode($places) }};
            var heatmapData = [];
            for (var i = 0; i < results.length; i++) {
                var coords = results[i];

                var latLng = new google.maps.LatLng(coords[0], coords[1]);
                heatmapData.push(latLng);

                if(mode === "markers")
                    var marker = new google.maps.Marker({
                        position: latLng,
                        map: map
                    });
            }

            if(mode === "heatmap")
                var heatmap = new google.maps.visualization.HeatmapLayer({
                    data: heatmapData,
                    dissipating: false,
                    radius: 0.01,
                    map: map
                });
        }
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZFqq8rHBHsgXAKm5NgcECUhWcYOh_x3Q&libraries=visualization&callback=initMap">
    </script>
@stop
