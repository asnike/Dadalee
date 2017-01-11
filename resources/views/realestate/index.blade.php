@extends('layouts.master')

@include('map.partial.map', ['realestates'=>$realestates, 'type'=>'realestate'])

@section('script')

    <script>
        var Controller = (function(){
            var map, realestates, markers = [], searchedInfo, init = function(){
                initMap();
                getRealestates();
                initBtnTab();
            },
            initBtnTab = function(){
                $('.btn-tab').click(function(e){
                    $('.btn-tab>.btn').removeClass('active');
                    $(e.target).addClass('active');
                });
            }
            initMap = function(){
                var mapContainer = document.getElementById('map'),
                    mapOption = {
                        center: new daum.maps.LatLng(37.5635710006, 126.9755292634),
                        level: 6
                    };
                map = new daum.maps.Map(mapContainer, mapOption);
            },
            onSubmit = function(){
                var name = $('#name').val(),
                    addr = $('#addr').val(),
                    own = $('#own').is(':checked');
                if(name && addr){
                    $.ajax({
                        dataType: 'json',
                        cache: false,
                        type:'POST',
                        url:'/realestates',
                        headers:{
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            name:name,
                            address:searchedInfo.title + (searchedInfo.buildingAddress ? searchedInfo.buildingAddress : ''),
                            lat:searchedInfo.lat,
                            lng:searchedInfo.lng,
                            own:own,
                        }
                    }).done(function(){
                        console.log('added!!');
                        //setMarker(searchedInfo.lat, searchedInfo.lng, true);

                        getRealestates();
                    });
                }
                return false;
            },
            searchCallback = function(result){
                console.log('result : ', result);
                searchedInfo = result;
            },
            setMarker = function(data, moveCenter){
                var coords = new daum.maps.LatLng(data.lat, data.lng);
                var marker = new daum.maps.Marker({
                    map: map,
                    position: coords
                }), markerData;
                if(moveCenter) map.setCenter(coords);
                markers[markers.length] = markerData = {data:data, marker:marker, click:function(){ showInfo(markerData); }};
                daum.maps.event.addListener(marker, 'click', markerData.click);

            },
            getRealestates = function(){
                $.ajax({
                    type:'GET',
                    url:'/realestates',
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{

                    }
                }).done(getRealestatesSuccess);
            },
            getRealestatesSuccess = function(data){
                var i, j, html;
                console.log(data);
                realestates = data.lists;

                for(i = 0, j = markers.length ; i < j ; i++){
                    daum.maps.event.removeListener(markers[i].marker, 'click', markers[i].click);
                    markers[i].marker.setMap(null);
                }
                markers = [];

                var template = Handlebars.compile($('#realestate-list-item').html());

                for(html = '', i = 0, j = data.lists.length ; i < j ; i++){
                    setMarker(data.lists[i], i == 0);
                    html += template({
                        'id':data.lists[i].id,
                        'name':data.lists[i].name,
                        'address':data.lists[i].address,
                    });
                }
                $('.realestate-list>.list-group-item').off();
                $('.realestate-list').html(html);
                $('.realestate-list>.list-group-item').click(focusRealestate);
            },
            showInfo = function(markerData){
                if(markerData.overlay) return;
                var template = Handlebars.compile($('#info-window').html()),
                    content = template({
                        'title':markerData.data.name,
                        'contents':markerData.data.address,
                        'id':markerData.data.id,
                    });

                var infowindow = new daum.maps.InfoWindow({
                    content:content,
                    removable:true
                });

                var overlay = new daum.maps.CustomOverlay({
                    content: content,
                    map: map,
                    position: markerData.marker.getPosition()
                });
                overlay.setMap(map);
                markerData.overlay = overlay;
            },
            focusRealestate = function(e){
                var i, j, id = $(this).attr('data-id'), idx, coords;
                for(i = 0, j = realestates.length ; i < j ; i++){
                    if(realestates[i].id == id){
                        idx = i;
                        break;
                    }
                }
                coords = new daum.maps.LatLng(realestates[idx].lat, realestates[idx].lng);
                map.setCenter(coords);
            },
            hideInfo = function(target) {
                console.log(target);

                var i, j, id = $(target).attr('data-id'), overlay;
                for(i = 0, j = markers.length ; i < j ; i++){
                    if(markers[i].data.id == id){
                        overlay = markers[i].overlay;
                        break;
                    }
                }
                overlay.setMap(null);
                markers[i].overlay = overlay = null;
            },
            openDetailModal = function(){
                $('#realestate-detail').modal();
            };
            init();
            return {
                onSubmit:onSubmit,
                searchCallback:searchCallback,
                hideInfo:hideInfo,
                openDetailModal:openDetailModal,
            }
        })();
    </script>
@stop

@include('realestate.partial.handlebars')
@include('realestate.partial.modal')