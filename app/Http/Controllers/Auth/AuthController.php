<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $user_service;
    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function login(LoginRequest $request)
    {
        return $this->user_service->login($request);
    }

    public function register(RegisterRequest $request)
    {
        return $this->user_service->register($request);
    }
}
