<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    const MAX_MONSTERS = 5;

    public function __construct()
    {
        /** @TODO: Implement javascript functionality */
        // $this->middleware('ajax')
        //     ->only('generateKey');
    }

    public function view($key, Request $request)
    {
        $user = User::where('key', $key)->firstOrFail();

        return view('pages.users.view', compact('user'));
    }

    public function drops($key, $monsters, Request $request)
    {
        $monsters = explode('-', $monsters);

        if (count($monsters) > self::MAX_MONSTERS) {
            abort(404);
        }

        $user = User::where('key', $key)->firstOrFail();

        $user->load(['kills' => function($query) use ($monsters) {
            $query->whereIn('monster_id', $monsters);
        }, 'kills.monster', 'kills.items']);

        return view('pages.users.drops', compact('user'));
    }

    public function generateKey()
    {
        if (!auth()->check()) {
            die('Fuck off!');
        }

        $user = auth()->user();

        $user->key = str_random(7);
        $user->save();

        return redirect()->back();

        /** @TODO: Implement javascript functionality */
        // return response()->json([
        //     'url' => route('users.view', ['key' => $user->key])
        // ]);
    }
}
