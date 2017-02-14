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

                $("select").select2();
            },
            initMap = function(){
                var mapContainer = document.getElementById('map'),
                    mapOption = {
                        center: new daum.maps.LatLng(37.5635710006, 126.9755292634),
                        level: 4
                    };
                map = new daum.maps.Map(mapContainer, mapOption);
                daum.maps.event.addListener(map, 'idle', mapBoundChange);

                clusterer = new daum.maps.MarkerClusterer({
                    map:map,
                    averageCenter:true,
                    minLevel:3
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
                var selectData, sizes, i, j, key;

                $('#selectedName').html(data.actualprices[0].building_name);
                $('#selectedAddr').html(data.actualprices[0].sigungu + ' '
                    + +data.actualprices[0].main_no + '-'
                    + +data.actualprices[0].sub_no);
                console.log('get history : ', data);

                for(selectData = [], sizes = {}, i = 0, j = data.actualprices.length ; i < j ; i++){
                    sizes[data.actualprices[i].exclusive_size] = 1;
                }
                i = 0;
                selectData[0] = {id:0, text:'전체'};
                for(key in sizes){
                    selectData[selectData.length] = {id:key, text:key};
                    i++;
                }
                //$('#actualPrices').html(html);
                $('#actualPricesTable').bootstrapTable('destroy');
                $('#actualPricesTable').bootstrapTable({data:data.actualprices});


                $('#tradeSize').select2('destroy');
                $('#tradeSize').html('');
                $('#tradeSize').select2({
                    data:selectData
                });
                $('#tradeSize').on('select2:select', function (e) {
                    var opt = $('#tradeSize').val() == 0 ? {} : {'exclusive_size':+$('#tradeSize').val()};
                    $('#actualPricesTable').bootstrapTable('filterBy', opt);
                });
            };
            init();
        })();
    </script>
@stop

@include('actualprice.partial.handlebars')