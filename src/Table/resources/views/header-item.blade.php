@if(!empty($item['order']))
    <a href="{{ $item->orderUrl() }}">
        {{ $item['name'] }}
        @if($item->currentOrder() == 'asc')
            <i class="fa fa-angle-up"></i>
        @elseif($item->currentOrder() == 'desc')
            <i class="fa fa-angle-down"></i>
        @else
            <i class="fa fa-sort"></i>
        @endif
        
    </a>
@else
    {{ $item['name'] }}
@endif