<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function view(User $user, Request $request)
    {
        return view('pages.users.view', compact('user'));
    }
}
