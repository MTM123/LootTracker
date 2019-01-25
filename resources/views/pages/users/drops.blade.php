@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-9">
            <div class="card mb-3">
                <div class="card-header">Graph</div>

                <div class="card-body">

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Monster list</div>
                    <?php
                    $killKc = count($user->kills);
                    $loot = [];

                    foreach ($user->kills as $kills) {
                        foreach ($kills->items as $items) {
                            $loot[] = ['id' => $items->id, 'price' => $items->price, 'name' => $items->name, 'qty' => $items->pivot->item_qty];
                        }
                    }

                    dd($loot);

                    echo $killKc;
                    //dd($user->kills->items)



                //dd($user->kills[0]->items[0]->price);
                ?>
                <div class="card-body">

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Drop List</div>

                <div class="card-body">

                </div>
            </div>
        </div>


    </div>
</div>
@endsection