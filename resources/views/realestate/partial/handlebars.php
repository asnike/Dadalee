<script id="info-window" type="text/x-handlebars-template">
    <div class="panel panel-default map-info-window" style="margin-top:-140px;">
        <div class="panel-heading">{{ title }}<button type="button" class="close" onclick="Controller.hideInfo(this)" data-id="{{ id }}"><i class="fa fa-close"></i></button></div>
        <div class="panel-body">
            {{ contents }}
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
            <div class="btn btn-info btn-sm btn-detail ladda-button" data-id="{{ id }}" data-style="zoom-in" data-size="1"><span class="ladda-label"><i class="fa fa-pencil-square-o"></i> 상세</span></div>
            <div class="btn btn-info btn-sm btn-price ladda-button" data-id="{{ id }}" data-style="zoom-in" data-size="1"><span class="ladda-label"><i class="fa fa-info-circle"></i> 실거래가</span></div>
            <div class="btn btn-danger btn-sm btn-del" data-id="{{ id }}"><i class="fa fa-trash-o"></i> 삭제</div>
        </div>
    </li>
</script>

<script id="option-item" type="text/x-handlebars-template">
    <option value="{{ value }}">{{ name }}</option>
</script>
