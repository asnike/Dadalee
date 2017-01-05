@extends('layouts.master')
@include('map.partial.map', ['realestates'=>$realestates, 'type'=>'pricetag'])

@section('script')
    <script>
        var Controller = (function(){
            var init = function(){
                $("select").select2();
            };
            init();
        })();
    </script>
@stop