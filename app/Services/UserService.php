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
        $find_user = $this->user->where('email', $request->email)
            ->first();

        if (!$find_user || !Hash::check($request->password, $find_user->password)) {
            return apiJsonResponse('error', ['email' => __('auth.failed')], __('auth.failed'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $this->respondWithToken($find_user);
        return apiJsonResponse('success', $data, __('auth.success'), Response::HTTP_OK);
    }

    protected function respondWithToken($user)
    {
        // Revoke all tokens...
        //TODO::enable this one when going for production
        $user->tokens()->delete();
        $api_scope = [];
        if ($user->user_type == 'admin')
            $api_scope = ['product:add,edit,delete,quantity-update', 'order:status-update', ''];

        $token = $user->createToken($user->name, $api_scope)->plainTextToken;
        return [
            'access_token' => explode('|', $token)[1],
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ];
    }

    public function register(Request $request, $user_type = 'user')
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $user_type
            ]);
            return $this->login($request);
        } catch (Exception $ex) {
            return apiJsonResponse('error', ['error' => $ex->getMessage()], __('custom.creation_failed'), Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
