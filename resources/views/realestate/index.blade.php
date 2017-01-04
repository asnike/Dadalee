@extends('layouts.master')

@include('map.partial.map', ['realestates', $realestates])

@section('script')
    <script>
        var Controller = (function(){
            var map, searchedInfo, init = function(){
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
                        setMarker();
                    });
                }
                return false;
            },
            searchCallback = function(result){
                console.log('result : ', result);
                searchedInfo = result;
            },
            setMarker = function(){
                var coords = new daum.maps.LatLng(searchedInfo.lat, searchedInfo.lng);
                var marker = new daum.maps.Marker({
                    map: map,
                    position: coords
                });
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