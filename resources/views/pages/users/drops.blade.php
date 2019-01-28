@extends('layouts.app')
@section('content')
<?php
use App\Http\Controllers\Api\MonsterLootController;
?>
{!!  "<script>;var graph_sort = '$drops->sortBy';</script>" !!}
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
                <div class="card-header">Monster list<span class="badge badge-primary float-right">{{ number_format($drops->totalLootSum,0,".", ",") }}</span></div>
                <div class="card-body price-check-loot">

                    @foreach($drops->drops as $id => $item)
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
                    <form method="POST" action="{{ route('filter.sort') }}">
                        <div class="form-group">
                            <label for="exampleFormControlSelect2">Sort</label>
                            @csrf
                            <select name="sortby" class="form-control">
                                @foreach(MonsterLootController::$sortSelect as $id => $v)
                                    <option @if($id == session(MonsterLootController::$SESSION_SORT_KEY)) selected @endif value="{{ $id }}">{{ $v['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Sort</button>

                    </form>
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