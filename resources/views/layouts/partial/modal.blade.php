<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="alert">
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
                <button type="button" class="btn btn-default">{{ trans('common.ok') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="confirm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('common.alert') }}</h4>
            </div>
            <div class="modal-body">
                <p class="contents"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success">{{ trans('common.ok') }}</button>
                <button type="button" class="btn btn-default">{{ trans('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="block" id="block">
    <div class="loader"></div>
</div>