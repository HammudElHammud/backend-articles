<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingUserController extends Controller
{
    /**
     * @param UserChangePasswordRequest $request
     * @return JsonResponse
     */
    public function update(UserChangePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();

        $params = $request->only(['old_password', 'password']);

        if (!Hash::check($params['old_password'], auth()->user()->getAuthPassword())) {
            return $this->errorResponse(__('Old password is not correct'), [], 404);
        }

        if ($user->update(['password' => Hash::make($params['password'])])) {

            Auth::user()->tokens()->delete();

            $token = $user->createToken('authentication')->accessToken;
            $data['access_token'] = $token;
            $data['user'] = $user;


            return $this->successResponse(__('Password changed successfully'), $data);
        }


        return $this->errorResponse(__('Password could not be changed'), []);
    }
}
