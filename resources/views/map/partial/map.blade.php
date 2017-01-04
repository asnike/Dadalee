@section('content')
    <div class="row">

        <form action="" class="search-form" onsubmit="onSubmit()">
            <div class="fomr-group clearfix">
                <div class="col-md-2"><input type="text" required class="form-control" id="name" placeholder="{{ trans('common.name') }}" /></div>
                <div class="col-md-5"><input type="text" required class="form-control" id="addr" readonly onfocus="sample2_execDaumPostcode()" placeholder="{{ trans('common.address') }}" /></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary">{{ trans('common.realestateAdd') }}</button></div>
            </div>
        </form>

    </div>
    <div class="row clearfix">
        <div class="col-md-8">
            <div id="map" style="width:100%;height:350px;"></div>
        </div>
        <div class="col-md-4"></div>
    </div>




    <div id="layer" style="display:none;position:fixed;overflow:hidden;z-index:1;-webkit-overflow-scrolling:touch;">
        <img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
    </div>

    <script type="text/javascript" src="//apis.daum.net/maps/maps3.js?apikey=ec5b7e847efe7670751fbc7fd1aa5e4a&libraries=services"></script>
    <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
    <script>
        var element_layer = document.getElementById('layer');

        function closeDaumPostcode() {
            element_layer.style.display = 'none';
        }

        function sample2_execDaumPostcode() {
            new daum.Postcode({
                oncomplete: function(data) {
                    var fullAddr = data.address;
                    var extraAddr = '';

                    if(data.addressType === 'R'){
                        if(data.bname !== ''){
                            extraAddr += data.bname;
                        }
                        if(data.buildingName !== ''){
                            extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                        }
                        fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                    }

                    $('#addr').val(data.jibunAddress);
                    console.log(data);
                    console.log(fullAddr);

                    element_layer.style.display = 'none';
                },
                width : '100%',
                height : '100%'
            }).embed(element_layer);

            element_layer.style.display = 'block';
            initLayerPosition();
        }

        function initLayerPosition(){
            var width = 300;
            var height = 460;
            var borderWidth = 5;

            element_layer.style.width = width + 'px';
            element_layer.style.height = height + 'px';
            element_layer.style.border = borderWidth + 'px solid';
            element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
            element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
        }
    </script>
@stop
