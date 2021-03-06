<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="alert" style="z-index:3000;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('common.alert') }}</h4>
            </div>
            <div class="modal-body">
                <p class="contents"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-ok">{{ trans('common.ok') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="confirm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('common.confirm') }}</h4>
            </div>
            <div class="modal-body">
                <p class="contents"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-ok">{{ trans('common.ok') }}</button>
                <button type="button" class="btn btn-default btn-cancel">{{ trans('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="block" id="block">
    <div class="loader"></div>
</div>