@section('sidebar')
<div class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-content">
            <div class="clearfix">
                <div class="btn-group btn-tab pull-right">
                    <a href="#own-tab" class="btn btn-default active" data-toggle="tab">{{ trans('common.own') }}</a>
                    <a href="#attension-tab" class="btn btn-default" data-toggle="tab">{{ trans('common.attension') }}</a>
                </div>
            </div>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="own-tab">

                    <ul class="list-group realestate-list realestate-own-list">
                        @foreach($realestates as $realestate)
                            @if($realestate->own)
                                <li class="list-group-item" data-id="{{ $realestate->id }}">
                                    <span>{{ $realestate->name }}</span>
                                    <div class="addr">{{ $realestate->address }}</div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane" id="attension-tab">

                    <ul class="list-group realestate-list realestate-attension-list">
                        @foreach($realestates as $realestate)
                            @if($realestate->own)
                                <li class="list-group-item" data-id="{{ $realestate->id }}">
                                    <span>{{ $realestate->name }}</span>
                                    <div class="addr">{{ $realestate->address }}</div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@stop