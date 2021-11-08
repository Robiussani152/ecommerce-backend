<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * login user
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $findUser = $this->user->where('email', $request->email)
            ->first();

        if (!$findUser || !Hash::check($request->password, $findUser->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $data = $this->respondWithToken($findUser);
        return apiJsonResponse('success', $data, __('auth.success'), Response::HTTP_OK);
    }

    protected function respondWithToken($user)
    {
        // Revoke all tokens...
        //TODO::enable this one when going for production
        $user->tokens()->delete();
        $apiScope = [];
        if ($user->user_type == 'admin')
            $apiScope = ['add-product', 'update-product', 'delete-product', 'product-stock-update', 'order-status-update'];

        $token = $user->createToken($user->name, $apiScope)->plainTextToken;
        return [
            'access_token' => explode('|', $token)[1],
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ];
    }

    public function register(Request $request, $userType = 'user')
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $userType
            ]);
            return $this->login($request);
        } catch (Exception $ex) {
            return apiJsonResponse('error', ['error' => $ex->getMessage()], __('custom.creation_failed'), Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
