<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function web(UserService $service)
    {
        $service->checkUser(false); // will cause a business error

        return view('welcome');
    }

    public function api(UserService $service)
    {
        $service->checkUser(false); // business bug

        return response()->json(['ok' => true]);
    }

    public function bug()
    {
        $a = new \stdClass();
        $a->boom(); // system bug
    }
}
