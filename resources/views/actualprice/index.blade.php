@extends('layouts.master')
@include('map.partial.map', ['realestates'=>[], 'type'=>'actualprices'])
@include('layouts.partial.info', ['type'=>'actualprice'])


@include('actualprice.partial.controlpanel')
@section('handlebars')
    @include('actualprice.partial.handlebars')
@stop



@section('script')
    <script>
        var Controller = (function(){
            var map,
                currZIndex,
                clusterer,
                markers = [],
                events = [],
                sigungu = [],
                searchConditions = {
                    type:0,
                    size:0,
                    year:0,
                },
            init = function(){
                initMap();
                initMapControl();
                getSigunguList();
                $(".select2").select2();
                $('.map-control-panel select').change(changeConditions);
            },
            changeConditions = function(e){
                searchConditions[$(this).attr('name')] = $(this).val();
                mapBoundChange();
            },
            getSigunguList = function(){
                U.http(getSigunguListEnd, 'sigungus/', {method:'GET'})
            },
            getSigunguListEnd = function(data){
                sigungu = data.lists;
                $('input[name="address"]').easyAutocomplete({
                    data: sigungu,
                    getValue:'full',
                    list: {
                        match: {
                            enabled: true
                        }
                    }
                });
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
            initMapControl = function(){
                var zoomControl = new daum.maps.ZoomControl();
                map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

                $('.btn-go').click(searchWhere);

            },
            searchWhere = function(e){
                var geocoder = new daum.maps.services.Geocoder(),
                    address = $('input[name="address"]').val();

                geocoder.addr2coord(address, function (status, result) {
                    if (status === daum.maps.services.Status.OK) {
                        console.log(result);

                        coords = new daum.maps.LatLng(result.addr[0].lat, result.addr[0].lng);
                        map.setCenter(coords);
                    }else{
                        console.log(status, result);
                    }
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
                U.http(getActualPricesEnd, 'actualprices/contain/'+ min + ',' + max + '?type=' + searchConditions.type +
                    '&size=' + searchConditions.size + '&year=' + searchConditions.year
                    ,{method:'GET'});
            },
            getActualPricesEnd = function(data){
                //console.log(data.lists);
                removeMarkers();
                setMarkers(data.lists);
            },
            removeMarkers = function () {
                $('.marker-content').attr('onclick', null).unbind('click');
                clusterer.clear();
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
                getRentalHistory(mainNo+','+subNo);
                getPcost(mainNo+','+subNo);
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

                $('.price-info').addClass('show');
                /*if(selectedTarget){
                    focusRealestate.apply(selectedTarget);
                    selectedTarget = null;
                }*/
            },
            getRentalHistory = function(bunji){
                U.http(getRentalHistoryEnd, 'rentalcosts/' + bunji, {method:'GET'})
            },
            getRentalHistoryEnd = function(data){
                var selectData, sizes, i, j, key;

                console.log('get history : ', data);

                for(selectData = [], sizes = {}, i = 0, j = data.rentalcosts.length ; i < j ; i++){
                    sizes[data.rentalcosts[i].exclusive_size] = 1;
                }
                i = 0;
                selectData[0] = {id:0, text:'전체'};
                for(key in sizes){
                    selectData[selectData.length] = {id:key, text:key};
                    i++;
                }
                //$('#actualPrices').html(html);
                $('#rentalCostsTable').bootstrapTable('destroy');
                $('#rentalCostsTable').bootstrapTable({data:data.rentalcosts});


                $('#rentalSize').select2('destroy');
                $('#rentalSize').html('');
                $('#rentalSize').select2({
                    data:selectData
                });
                $('#rentalSize').on('select2:select', function (e) {
                    var opt = $('#rentalSize').val() == 0 ? {} : {'exclusive_size':+$('#rentalSize').val()};
                    $('#rentalCostsTable').bootstrapTable('filterBy', opt);
                });
            },
            getPcost = function(bunji){
                U.http(getPcostEnd, 'actualprices/pcost/' + bunji, {method:'GET'})
            },
            getPcostEnd = function(data){
                console.log(data);
                $('.avg-value').html(numeral(data.data.average_price).format('0,0'));
                $('.pcost-value').html(numeral(data.data.pcost).format('0,0'));
            },
            closePriceInfo = function(){
                $('.price-info.show').removeClass('show');
            };
            init();
            console.log('sigungu :: ', sigungu);
            return {
                closePriceInfo:closePriceInfo,
            }
        })();
    </script>
@stop

