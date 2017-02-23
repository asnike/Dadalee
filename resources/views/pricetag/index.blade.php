@extends('layouts.master')
@include('map.partial.map', ['realestates'=>$realestates, 'type'=>'pricetag'])
@include('pricetag.partial.navbarsub')
@include('pricetag.partial.sidebar')
@include('layouts.partial.info', ['type'=>'pricetag'])
@section('handlebars')
    @include('pricetag.partial.handlebars')
@stop

@section('script')
    <script>
        var Controller = (function(){
            var selectedRealestate,
                searchedInfo = {},
                addrInfo = {},
                clusterer,
                markers = [],
                events = [],
            init = function(){
                initMap();
                initMapControl();
                initAddForm();
                getSideList();
                $("select").select2();
            },
            initAddForm = function () {
                $('#reported_at').datetimepicker({format:'YYYY.MM.DD', icons:{
                    previous: 'fa fa-arrow-left',
                    next: 'fa fa-arrow-right',
                }});
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
                $(window).resize(function(){
                    map.relayout();
                });
            },
            onSubmit = function(){
                var data = U.Form.getValueWithForm('form[name="priceTagForm"]');
                console.log(data);
                data = $.extend(data, {
                        method:'POST',
                        sigungu:searchedInfo.localName_1+' '+searchedInfo.localName_2+' '+searchedInfo.localName_3,
                        main_no:searchedInfo.mainAddress,
                        sub_no:searchedInfo.subAddress,
                        building_name:addrInfo.buildingName,
                        new_address:searchedInfo.newAddress,
                        lat:searchedInfo.lat,
                        lng:searchedInfo.lng,
                    });
                U.http(getSideList, '/pricetags', data);
                return false;
            },
            initMapControl = function(){
                var zoomControl = new daum.maps.ZoomControl();
                map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);
            },
            mapBoundChange = function(){
                var bounds = map.getBounds(),
                    swLatlng = bounds.getSouthWest(),
                    neLatlng = bounds.getNorthEast();

                getPriceTags(swLatlng.toString(), neLatlng.toString());
            },
            getPriceTags = function(min ,max){
                min = min.replace(/[\(\)\s]/g, '');
                max = max.replace(/[\(\)\s]/g, '');
                U.http(getPriceTagsEnd, 'pricetags/contain/'+ min + ',' + max,{method:'GET'});
            },
                getPriceTagsEnd = function(data){
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
            makeCustomOverlay = function(data, idx){
                var template = Handlebars.compile($('#marker-template').html()),
                    content = template({
                        name:data.building_name,
                        price:numeral(data.price).format('0,0'),
                        size:data.exclusive_size,
                        year:data.completed_at,
                        main_no:data.main_no,
                        sub_no:data.sub_no,
                        bunji:data.main_no+','+data.sub_no,
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
            getSideList  = function(data){
                if(data && data.msg) U.Modal.alert(data.msg);
                U.http(getSideListEnd, '/pricetags', {method:'GET'});
            },
            getSideListEnd = function(data){
                console.log(data);

                var i, j, html;
                realestates = data.lists;


                var template = Handlebars.compile($('#realestate-list-item').html());

                for(html = '', i = 0, j = data.lists.length ; i < j ; i++){
                    realestate = data.lists[i];
                    tmplData = {
                        'id': realestate.id,
                        'name': realestate.building_name,
                        'address': realestate.address,
                        'bunji':realestate.main_no+','+realestate.sub_no
                    };
                    html += template(tmplData);
                }
                var emptyTmpl = Handlebars.compile($('#realestate-list-no-item').html());
                if(html == '') html = emptyTmpl();

                $('.realestate-own-list>.list-group-item').off();
                $('.realestate-own-list').html(html);
                $('.realestate-own-list>.list-group-item').click(focusRealestate);


                $('.realestate-list>.list-group-item>.tools>.btn-detail').off();
                $('.realestate-list>.list-group-item>.tools>.btn-price').off();
                $('.realestate-list>.list-group-item>.tools>.btn-del').off();
                $('.realestate-list>.list-group-item').click(focusRealestate);
                $('.realestate-list>.list-group-item>.tools>.btn-detail').click(openDetailModal);
                $('.realestate-list>.list-group-item>.tools>.btn-price').click(showPrice);
                $('.realestate-list>.list-group-item>.tools>.btn-del').click(delConfirm);
            },
            focusRealestate = function(e){
                var i, j, id = $(this).attr('data-id'), idx, coords, projection;
                if(!id) return;
                for(i = 0, j = realestates.length ; i < j ; i++){
                    if(realestates[i].id == id){
                        idx = i;
                        break;
                    }
                }
                coords = new daum.maps.LatLng(realestates[idx].lat, realestates[idx].lng);
                if($('.price-info.show')[0]){
                    projection = map.getProjection();
                    point = projection.pointFromCoords(coords);
                    point.x -= 215;
                    coords = projection.coordsFromPoint(point);
                }
                map.setCenter(coords);
            },
            openDetailModal = function(e){

            },
            showPrice = function(e){
                var bunji = $(this).attr('data-bunji'),
                    id = $(this).attr('data-id'),
                    i, j;

                for(i = 0, j = realestates.length ; i < j ; i++){
                    if(id == realestates[i].id) selectedRealestate = realestates[i];
                }

                getMyLog(bunji);
                getPriceHistory(bunji);
                getRentalHistory(bunji);
                getPcost(bunji);
                //console.log(e.target, e.currentTarget);
                selectedTarget = e ? e.currentTarget : null;
            },
            getPriceHistory = function(bunji){
                U.http(getPriceHistoryEnd, 'actualprices/' + bunji, {method:'GET'})
            },
            getPriceHistoryEnd = function(data){
                var selectData, sizes, i, j, key;

                $('#selectedName').html(selectedRealestate.building_name);
                $('#selectedAddr').html(selectedRealestate.sigungu + ' ' + selectedRealestate.main_no + (+selectedRealestate.sub_no?'-'+ selectedRealestate.sub_no:''));
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
                if(selectedTarget){
                    focusRealestate.apply(selectedTarget);
                    selectedTarget = null;
                }
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
            getMyLog = function(bunji){
                U.http(getMyLogEnd, 'pricetags/' + bunji, {method:'GET'})
            },
            getMyLogEnd = function(data){
                var selectData, sizes, i, j, key;

                for(selectData = [], sizes = {}, i = 0, j = data.pricetags.length ; i < j ; i++){
                    sizes[data.pricetags[i].exclusive_size] = 1;
                }
                i = 0;
                selectData[0] = {id:0, text:'전체'};
                for(key in sizes){
                    selectData[selectData.length] = {id:key, text:key};
                    i++;
                }
                //$('#actualPrices').html(html);
                $('#myLogTable').bootstrapTable('destroy');
                $('#myLogTable').bootstrapTable({
                    data:data.pricetags,
                    iconSize:'sm'
                });


                $('#myLogSize').select2('destroy');
                $('#myLogSize').html('');
                $('#myLogSize').select2({
                    data:selectData
                });
                $('#myLogSize').on('select2:select', function (e) {
                    var opt = $('#myLogSize').val() == 0 ? {} : {'exclusive_size':$('#myLogSize').val()};
                    $('#myLogTable').bootstrapTable('filterBy', opt);
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
            },
            delConfirm = function(e){
                var id = $(this).attr('data-id');

                U.Modal.confirm({msg:'삭제하시겠습니까?', callback:function(){del(id);}});
            },
            del = function(id){
                U.http(delEnd, '/realestates/' + id, {method:'POST','_method':'delete'})
            },
            delEnd = function(data){
                if(data.result){
                    U.Modal.confirmClose();
                }
            },
            searchCallback = function(result, data){
                console.log('result : ', result, data);
                searchedInfo = result;
                addrInfo = data;
            };
            init();
            return {
                onSubmit: onSubmit,
                searchCallback:searchCallback,
                closePriceInfo:closePriceInfo,
                showPrice:showPrice,
            }
        })();
    </script>
@stop