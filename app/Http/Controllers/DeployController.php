<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        $root_path = base_path();
        echo shell_exec("cd $root_path;./deploy.sh");
    }
}
