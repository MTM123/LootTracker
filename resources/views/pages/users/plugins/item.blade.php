<div class="item" data-toggle="tooltip" data-placement="bottom" title="{{ $item->name }}">
    <span>{{ $item->pivot->item_qty }}</span>
    <img src="{{ env('CDN_URL') }}{{ $item->item_id }}.png" />
</div>