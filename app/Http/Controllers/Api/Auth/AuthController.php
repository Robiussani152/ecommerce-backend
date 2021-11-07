<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function getAuthUser(Request $request)
    {
        return apiJsonResponse('success', new UserResource($request->user()), 'Get auth user data.', Response::HTTP_OK);
    }
}
