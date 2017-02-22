@section('sidebar')
<div class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-content">
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
    </div>
</div>
@stop