@extends('layouts.master')

@include('map.partial.map')

@section('script')
    <script>
        var mapContainer = document.getElementById('map'),
            mapOption = {
                center: new daum.maps.LatLng(33.450701, 126.570667),
                level: 3
            };

        var map = new daum.maps.Map(mapContainer, mapOption);
    </script>
@stop