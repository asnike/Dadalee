<script id="info-window" type="text/x-handlebars-template">
    <div class="panel panel-default">
        <div class="panel-heading">{{ title }}<button type="button" class="close" onclick="Controller.hideInfo(this)" data-id="{{ id }}"><i class="fa fa-close"></i></button></div>
        <div class="panel-body">
            {{ contents }}
        </div>
    </div>
</script>