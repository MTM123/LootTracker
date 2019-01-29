<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\DropRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class LootController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var DropRepository
     */
    protected $dropRepository;

    /**
     * LootController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        DropRepository $dropRepository
    ) {
        $this->userRepository = $userRepository;
        $this->dropRepository = $dropRepository;
    }

    /**
     * @param Request $request
     *
     * @return array|bool|null|string
     */
    public function post(Request $request)
    {
        if ($request->q == null || empty($request->q)) {
            return false;
        }

        $data = $this->userRepository->parseJsonData($request->q);

        $user = $this->userRepository->getUserByApiToken($data->api_token);

        if ($user == null) {
            return false;
        }

        return $this->userRepository->updateUserKills($user, $data);
    }

    public function get7DayLoot() {
        if (!auth()->check()) {
            die('Fuck off!');
        }

        $user = auth()->user();

        $allKills = $this->dropRepository->last7DaysDrops($user->key);
        $formated = $this->dropRepository->formatForGraphData($allKills);

        //return view('home');
        return response()->json($formated);
    }
}