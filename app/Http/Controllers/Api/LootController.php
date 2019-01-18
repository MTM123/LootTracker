<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class LootController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * LootController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
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
}