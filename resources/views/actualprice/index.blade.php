@extends('layouts.master')
@include('map.partial.map', ['realestates'=>[], 'type'=>'actualprice'])

@section('script')
    <script>
        var Controller = (function(){
            var map,
                clusterer,
            init = function(){
                initMap();
            },
            initMap = function(){
                var mapContainer = document.getElementById('map'),
                    mapOption = {
                        center: new daum.maps.LatLng(37.5635710006, 126.9755292634),
                        level: 6
                    };
                map = new daum.maps.Map(mapContainer, mapOption);
                daum.maps.event.addListener(map, 'idle', mapBoundChange);

                clusterer = new daum.maps.MarkerClusterer({
                    map:map,
                    averageCenter:true,
                    minLevel:4
                });
            },
            mapBoundChange = function(){
                var bounds = map.getBounds(),
                    swLatlng = bounds.getSouthWest(),
                    neLatlng = bounds.getNorthEast();

                getActualPrices(swLatlng.toString(), neLatlng.toString());
            },
            getActualPrices = function(min ,max){
                min = min.replace(/[\(\)\s]/g, '');
                max = max.replace(/[\(\)\s]/g, '');
                U.http(getActualPricesEnd, 'actualprices/contain/'+ min + ',' + max,{method:'GET'});
            },
            getActualPricesEnd = function(data){
                //console.log(data.lists);
                setMarkers(data.lists);
            },
            setMarkers = function(prices){
                var markers, last = {}, i, j;

                for(markers = [], i = 0, j = prices.length ; i < j ; i++){
                    data = prices[i];
                    if(data.main_no != last.main_no && data.sub_no != last.sub_no){
                        markers[markers.length] = makeCustomOverlay(data);//makeBasicMarker(data);
                        last = data;
                    }
                }
                clusterer.clear();
                clusterer.addMarkers(markers);
            },
            makeBasicMarker = function(data){
                return new daum.maps.Marker({
                    position : new daum.maps.LatLng(data.lat, data.lng)
                });
            }
            makeCustomOverlay = function(data){
                var content =
                    '<div style="background: #fff;border:1px solid #9d9d9d; border-radius: 5px;padding:4px;">'+
                        '<span style="font-size:12px;">'+ data.building_name +'</span>'+
                    '</div>',
                position = new daum.maps.LatLng(data.lat, data.lng),
                customOverlay = new daum.maps.CustomOverlay({
                    position: position,
                    content: content,
                    xAnchor: 0.3,
                    yAnchor: 0.91
                });

                return customOverlay;
            };
            init();
        })();
    </script>
@stop