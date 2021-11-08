<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Broadcast;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;

class AuthController extends Controller
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        return $this->userService->login($request);
    }

    public function register(RegisterRequest $request)
    {
        return $this->userService->register($request);
    }

    public function getAuthUser(Request $request)
    {
        return apiJsonResponse('success', new UserResource($request->user()), 'Get auth user data.', Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return apiJsonResponse('success', [], 'Successfully logged out!', Response::HTTP_OK);
    }
}
