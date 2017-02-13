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
                    minLevel:2
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

                $('.marker-content').attr('onclick', null).unbind('click');
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
                        main_no:data.main_no,
                        sub_no:data.sub_no,
                        id:data.id,
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
            },
            markerClick = function(target){
                var id = $(target).attr('data-id'),
                    mainNo = $(target).attr('data-main-no'),
                    subNo = $(target).attr('data-sub-no');
                console.log('click ', id, mainNo, subNo, target, this);
                $('.marker-content.selected').removeClass('selected');
                $(target).addClass('selected');
                getPriceHistory(mainNo+','+subNo);
            },
            getPriceHistory = function(bunji){
                U.http(getPriceHistoryEnd, 'actualprices/' + bunji, {method:'GET'})
            },
            getPriceHistoryEnd = function(data){
                $('#selectedName').html(data.actualprices[0].building_name);
                $('#selectedAddr').html(data.actualprices[0].sigungu + ' '
                    + +data.actualprices[0].main_no + '-'
                    + +data.actualprices[0].sub_no);
                console.log('get history : ', data);
                var html = '', i, j, template = Handlebars.compile($('#actual-price').html()), content;
                for(i = 0, j = data.actualprices.length ; i < j ; i++){
                    item = data.actualprices[i];
                    html += template({
                        size:item.exclusive_size,
                        year:item.yearmonth.substr(0, 4),
                        month:item.yearmonth.substr(4, 2),
                        day:item.day,
                        price:numeral(item.price).format('0,0'),
                    });
                }
                //$('#actualPrices').html(html);
                $('#actualPricesTable').bootstrapTable('destroy');
                $('#actualPricesTable').bootstrapTable({data:data.actualprices});
            };
            init();
        })();
    </script>
@stop

@include('actualprice.partial.handlebars')