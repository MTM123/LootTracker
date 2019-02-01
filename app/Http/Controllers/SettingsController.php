<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function settingsPage() {


        Breadcrumb()->add("Settings", route("page.setting"));
        return view('pages.users.setting');
    }
}
