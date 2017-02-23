<script id="marker-template" type="text/x-handlebars-template">
<div class="marker">
    <div class="marker-content" onclick="Controller.showPrice.apply(this,event);" data-bunji="{{ bunji }}" data-id="{{ id }}" data-main-no="{{ main_no }}" data-sub-no="{{ sub_no }}">
        <span class="name">{{ name }}</span>
        <div class="price">{{ price }}만</div>
        <div class="size">{{ size }}m&sup2;</div>
        <div class="year">{{ year }}년</div>
    </div>
</div>
</script>

<script id="realestate-list-item" type="text/x-handlebars-template">
    <li class="list-group-item clearfix" data-id="{{ id }}">
        <div class="info">
            <h5>{{ name }}</h5>
            <span class="addr">{{ address }}</span>
        </div>

        <div class="tools">
            <!--<div class="btn btn-success btn-xs btn-detail ladda-button" data-id="{{ id }}" data-style="zoom-in" data-size="1"><span class="ladda-label"><i class="fa fa-info-circle"></i> 정보</span></div>-->
            <div class="btn btn-success btn-xs btn-price ladda-button" data-id="{{ id }}" data-bunji="{{ bunji }}" data-style="zoom-in" data-size="1"><span class="ladda-label"><i class="fa fa-won"></i> 실거래가</span></div>
            <div class="btn btn-danger btn-xs btn-del" data-id="{{ id }}"><i class="fa fa-remove"></i> 삭제</div>
        </div>
    </li>
</script>

<script id="realestate-list-no-item" type="text/x-handlebars-template">
    <li class="list-group-item clearfix" data-id="{{ id }}">
        등록된 정보가 없습니다.
    </li>
</script>