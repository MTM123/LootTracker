@extends('layouts.app')
@section('content')
<?php
use App\Http\Controllers\Api\MonsterLootController;
?>
{!!  "<script>var graph_sort = '$drops->sortBy';</script>" !!}

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
                <div class="card-header">Price check<span class="badge badge-primary float-right">{{ number_format($drops->totalLootSum,0,".", ",") }}</span></div>
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
                            <label>Sort</label>
                            @csrf
                            <select name="sortby" class="form-control">
                                @foreach(MonsterLootController::$sortSelect as $id => $v)
                                    <option @if($id == session(MonsterLootController::$SESSION_SORT_KEY)) selected @endif value="{{ $id }}">{{ $v['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>From</label>
                            <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                <input type="text" name="from" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>To</label>
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text" name="to" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Sort</button>

                    </form>
                </div>
            </div>

            <div class="card last-5kills">
                <div class="card-header">Last 10 kills</div>
                <ul class="list-group list-group-flush">
                    <?php
                    $i = 0;
                    ?>
                    @foreach($user->kills as $kills)
                        <?php
                            $total_val = 0;
                            foreach ($kills->items as $item){
                                $total_val +=  $item->price*$item->pivot->item_qty;
                            }
                            ?>
                        <li class="list-group-item">
                            <div class="monster-name">Value: {{ number_format($total_val,0,".", ",") }}<span class="float-right">{{ $kills->created_at }}</span></div>
                            <div class="monster-drops">
                                @foreach($kills->items as $item)
                                    @include('pages.users.plugins.item', $item)
                                @endforeach
                            </div>
                        </li>
                            <?php
                            if (++$i == 10) break;
                            ?>
                    @endforeach
                </ul>
            </div>

        </div>


    </div>


@endsection