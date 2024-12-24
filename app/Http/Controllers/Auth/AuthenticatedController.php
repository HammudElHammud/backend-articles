<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LogoutUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class AuthenticatedController extends Controller
{

    protected $client;

    public function __construct()
    {
        $this->client = DB::table('oauth_clients')->where('password_client', 1)->first();
    }


    /**
     * User Login Request
     *
     * This endpoint allows you to get token and user
     *
     * @queryParam username required
     * @queryParam password required
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|max:100',
        ]);

        $email = $request->get('email');
        $password = $request->get('password');

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();


            $request->request->add([
                'username' => $email,
                'password' => $password,
                'grant_type' => 'password',
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'scope' => '*'
            ]);

            $proxy = Request::create(
                'oauth/token',
                'POST'
            );
            $response = Route::dispatch($proxy);


            if ($response->status() === 200) {
                $success = json_decode($response->getContent(), true);
                $success['user_id'] = $user->id;
                $success['email'] = $user->email;
                $success['name'] = $user->name;

                return $this->successResponse('success', $success, 200);
            } else {
                $error = json_decode($response->getContent(), true);
                return $this->errorResponse($error['message'], [], $response->status());
            }
        } else {
            return $this->errorResponse(__('login.login_failed'), [], Response::HTTP_UNAUTHORIZED);
        }
    }



    /**
     * User Logout Request
     *
     * This endpoint allows user remove token from the database and logout
     */
    public function destroy(LogoutUserRequest $request): JsonResponse
    {
        if (Auth::check()) {

            $user = Auth::user();


            Auth::user()->tokens()->delete();

            return $this->successResponse(__('Successfully logout'), []);
        }

        return $this->errorResponse(__("Unauthorized access"), []);
    }
}
