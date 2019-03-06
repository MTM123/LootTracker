<?php

namespace App\Http\Controllers\Users;

use App\Repositories\DropRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    const MAX_MONSTERS = 5;

    /**
     * @var DropRepository
     */
    protected $dropRepository;

    /**
     * @var Route
     */
    protected $route;

    public function __construct(
        DropRepository $dropRepository,
        Route $route
    )
    {
        $this->dropRepository = $dropRepository;
        $this->route = $route;
        /** @TODO: Implement javascript functionality */
        // $this->middleware('ajax')
        //     ->only('generateKey');
    }

    public function view($key, Request $request)
    {
        $user = User::where('key', $key)->firstOrFail();

        Breadcrumb()->add(($user->name==null?$user->email:$user->name), route("users.view", ['key' => $key]));
        return view('pages.users.view', compact('user'));
    }

    public function drops($key, $monsters, Request $request)
    {
        $monsters = explode('-', $monsters);

        if (count($monsters) > self::MAX_MONSTERS) {
            abort(404);
        }

        $monsters = array_unique($monsters);

        $user = User::where('key', $key)->firstOrFail();

        //dd($reques);

        $user->load(['kills' => function($query) use ($monsters) {
            $query->whereIn('monster_id', $monsters);
            $query->orderBy('created_at', 'DESC');
        }, 'kills.monster', 'kills.items']);

        $drops = $this->dropRepository->sortDrops($user);

        Breadcrumb()->add(($user->name==null?$user->email:$user->name), route("users.view", ['key' => $key]));
        Breadcrumb()->add("Drops", route("users.drops", ['key' => $key, 'monsters' => implode("-", $monsters)]));
        return view('pages.users.drops', compact('user', 'drops'));
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
