<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonsterLootController extends Controller
{

    public function __construct() {

    }


    public function post(Request $request)
    {
        return response()->json(session("monster_stacked_loot"));
    }
}