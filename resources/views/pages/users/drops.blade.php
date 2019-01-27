@extends('layouts.app')

@section('content')
<?php
$killKc = count($user->kills);
$loot = [];
$stackedLoot = [];
$totalLootSum = 0;

foreach ($user->kills as $kills) {
    foreach ($kills->items as $items) {
        $loot[] = ['id' => $items->item_id, 'price' => $items->price, 'name' => $items->name, 'qty' => $items->pivot->item_qty];
        $totalLootSum += $items->pivot->item_qty*$items->price;
        //Stack loot
        if (array_key_exists($items->id, $stackedLoot)) {
            $stackedLoot[$items->id]->qty += $items->pivot->item_qty;
            $stackedLoot[$items->id]->drop_times += 1;
            $stackedLoot[$items->id]->total_price += $items->pivot->item_qty*$items->price;
        }else{
            $stackedLoot[$items->id] = new \stdClass();
            $stackedLoot[$items->id]->id = $items->item_id;
            $stackedLoot[$items->id]->price = $items->price;
            $stackedLoot[$items->id]->name = $items->name;
            $stackedLoot[$items->id]->qty = $items->pivot->item_qty;
            $stackedLoot[$items->id]->drop_times = 1;
            $stackedLoot[$items->id]->total_price = $items->pivot->item_qty*$items->price;
        }
    }
}

//Sorting function
$sortBy = "total_price"; //Add this to filter ['id','price','name','qty','drop_times','total_price',]
$sortFlip = false;
$sortedLoot = [];
foreach ($stackedLoot as $killsNooneCare) {
    $biggestSortById = 0;
    $biggestSortByValue = 0;
    foreach ($stackedLoot as $id => $kills){
        $val = ((array)$kills)[$sortBy];
        if ($biggestSortByValue <= $val && !array_key_exists($id, $sortedLoot)){
            $biggestSortById = $id;
            $biggestSortByValue = $val;
        }
    }
    $sortedLoot[$biggestSortById] = $stackedLoot[$biggestSortById];
}


if ($sortFlip) {
    $sortedLoot = array_reverse($sortedLoot, true);
}

//get ride of key values
$resort = [];
foreach ($sortedLoot as $id => $kills) {
    $resort[] = $kills;
}
$sortedLoot = $resort;


session(['monster_stacked_loot' => $sortedLoot,'monster_loot' => $loot, 'monster_kc' => $killKc]);

?>
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header">Graph</div>

                <div class="card-body">
                    <div class="loot-chart-content">
                        {{--<canvas id="loot-chart"></canvas>--}}
                        <div id="chartdiv"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row justify-content-center">

        <div class="col-md-9">

            <div class="card mb-3">
                <div class="card-header">Monster list<span class="badge badge-primary float-right">{{ number_format($totalLootSum,0,".", ",") }}</span></div>
                <div class="card-body price-check-loot">

                    @foreach($sortedLoot as $id => $item)
                        <div class="item_container">
                            <div class="item" data-toggle="tooltip" data-placement="bottom" title="{{ $item->name }}">
                                <span>{{ $item->qty }}</span>
                                <img src="{{ env('CDN_URL') }}/media/{{ $item->id }}.png" />
                            </div>
                            <div class="price-values">
                                {{ $item->qty }} x {{ $item->price }}<br>
                                = {{ number_format($item->qty*$item->price,0,".", ",") }}
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-header">Filter</div>

                <div class="card-body">

                </div>
            </div>

            <div class="card">
                <div class="card-header">Drop List</div>

                <div class="card-body">

                </div>
            </div>
        </div>


    </div>

</div>
@endsection