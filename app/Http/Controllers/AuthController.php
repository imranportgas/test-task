<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $result = $this->userService->createUser($request->all());

        return response()->json(['data' => $result]);
    }

    public function login(Request $request)
    {
        $result = $this->userService->loginUser($request->all());

        return response()->json(['data' => $result]);
    }
}
