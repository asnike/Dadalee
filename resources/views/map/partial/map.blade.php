@section('content')



    <div id="map" class="{{ $type == 'actualprices'?'actual-prices':'' }}">

        <div class="btn-add mobile">
            <i class="fa fa-plus"></i>
        </div>
    </div>





    <div id="layer" style="display:none;position:fixed;overflow:hidden;z-index:5000;-webkit-overflow-scrolling:touch;">
        <img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
    </div>

    <script type="text/javascript" src="//apis.daum.net/maps/maps3.js?apikey=ec5b7e847efe7670751fbc7fd1aa5e4a&libraries=services,clusterer"></script>
    <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
    <script>
        var element_layer = document.getElementById('layer');

        function closeDaumPostcode() {
            element_layer.style.display = 'none';
        }

        function daumAddressOpen() {
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

                    if(U.global.isDetailModalOpened) $('#basicPanel input[name="address"]').val(data.jibunAddress);
                    else $('#addr').val(data.jibunAddress);
                    console.log(data);
                    console.log(fullAddr);

                    addr2coord(data);

                    element_layer.style.display = 'none';
                },
                width : '100%',
                height : '100%'
            }).embed(element_layer);

            element_layer.style.display = 'block';
            initLayerPosition();
        }

        function initLayerPosition(){
            var width = 450;
            var height = 460;
            var borderWidth = 5;

            element_layer.style.width = width + 'px';
            element_layer.style.height = height + 'px';
            element_layer.style.border = borderWidth + 'px solid';
            element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
            element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
        }

        function addr2coord(data) {
            var geocoder = new daum.maps.services.Geocoder();

            geocoder.addr2coord(data.jibunAddress, function (status, result) {
                if (status === daum.maps.services.Status.OK) {
                    if (typeof Controller.searchCallback == 'function') {
                        Controller.searchCallback(result.addr[0], data);
                    }
                }else{
                    console.log(status, result);
                }
            });
        }
    </script>
@stop
