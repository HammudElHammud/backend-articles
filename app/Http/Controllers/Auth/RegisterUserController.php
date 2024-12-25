<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class RegisterUserController extends Controller
{
    /**
     * User Register Request
     *
     * This endpoint allows you to register.
     *
     * @param RegisterNewUserRequest $request
     * @return JsonResponse
     */
    public function store(RegisterNewUserRequest $request): JsonResponse
    {
        $params = $request->only(['name', 'email', 'password']);

        $params['password'] = Hash::make($params['password']);

        $user = User::create($params);

        Auth::login($user);

        if ($user) {

            $user->save();

            $token = $user->createToken('authentication')->accessToken;
            $data['access_token'] = $token;
            $data['user_id'] = $user->id;
            $data['email'] = $user->email;
            $data['name'] = $user->name;

            return $this->successResponse(__('Register successfully'), $data);
        } else {
            return $this->errorResponse(__('Register failed'), []);
        }
    }
}
