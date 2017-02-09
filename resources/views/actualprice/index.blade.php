@extends('layouts.master')
@include('map.partial.map', ['realestates'=>[], 'type'=>'actualprice'])

@section('script')
    <script>
        var Controller = (function(){
            var map,
                currZIndex,
                clusterer,
                markers = [],
                events = [],
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
                var marker, last = {}, i, j;

                for(markers = [], events = [], i = 0, j = prices.length ; i < j ; i++){
                    data = prices[i];
                    if(data.main_no != last.main_no && data.sub_no != last.sub_no){
                        marker = markers[markers.length] = makeCustomOverlay(data, i);//makeBasicMarker(data);
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
            makeCustomOverlay = function(data, idx){
                var template = Handlebars.compile($('#marker-template').html()),
                    content = template({
                        name:data.building_name,
                        price:numeral(data.price).format('0,0'),
                        size:data.exclusive_size,
                        year:data.completed_at,
                    });

                position = new daum.maps.LatLng(data.lat, data.lng),
                customOverlay = new daum.maps.CustomOverlay({
                    position: position,
                    content: content,
                    xAnchor: 0.1,
                    yAnchor: 1,
                    zIndex: idx
                });
                currZIndex = idx;

                return customOverlay;
            };
            init();
        })();
    </script>
@stop

@include('actualprice.partial.handlebars')