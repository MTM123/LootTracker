<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class MonsterLootController extends Controller
{
    public static $SESSION_SORT_KEY = 'selected_filter';

    public static $sortSelect = [
        //['sort' => 'id', 'name' => 'ID'],
        ['sort' => 'price', 'name' => 'Price'],
        //['sort' => 'name', 'name' => 'Name'],
        ['sort' => 'qty', 'name' => 'Quantity'],
        ['sort' => 'drop_times', 'name' => 'Dropped time'],
        ['sort' => 'total_price', 'name' => 'Total value'],
    ];

    public function __construct() {

    }

    public function postFilter(Request $request)
    {
        if(array_key_exists($request->sortby, self::$sortSelect)){
            $request->session()->put(MonsterLootController::$SESSION_SORT_KEY, $request->sortby);
            $request->session()->save();
        }else{
            $request->session()->put(MonsterLootController::$SESSION_SORT_KEY, 0);
            $request->session()->save();
        }

        return redirect()->back();
    }


    public function post(Request $request)
    {
        return response()->json(session("monster_stacked_loot"));
    }
}