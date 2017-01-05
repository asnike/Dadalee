@extends('layouts.master')

@include('map.partial.map', ['realestates'=>$realestates, 'type'=>'realestate'])

@section('script')
    <script>
        var Controller = (function(){
            var map, realestates, searchedInfo, init = function(){
                initMap();
                getRealestates();

                $('.realestate-list>.list-group-item').click(focusRealestate);
            },
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
                    addr = $('#addr').val();
                if(name && addr){
                    $.ajax({
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
                        }
                    }).done(function(){
                        console.log('added!!');
                        setMarker(searchedInfo.lat, searchedInfo.lng, true);
                    });
                }
                return false;
            },
            searchCallback = function(result){
                console.log('result : ', result);
                searchedInfo = result;
            },
            setMarker = function(lat, lng, moveCenter){
                var coords = new daum.maps.LatLng(lat, lng);
                var marker = new daum.maps.Marker({
                    map: map,
                    position: coords
                });
                if(moveCenter) map.setCenter(coords);
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
                console.log(data);
                realestates = data.lists;
                for(var i = 0, j = data.lists.length ; i < j ; i++){
                    setMarker(data.lists[i].lat, data.lists[i].lng, i == 0);
                }
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
            };
            init();
            return {
                onSubmit:onSubmit,
                searchCallback:searchCallback,
            }
        })();
    </script>
@stop