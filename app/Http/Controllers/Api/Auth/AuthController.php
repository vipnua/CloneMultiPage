<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiLoginRequest;
use App\Http\Requests\Auth\ApiRegisterRequest;
use App\Http\Resources\Auth\AuthResource;
use App\User;
use Auth;

class AuthController extends Controller
{
    /**
     * Handle a login request
     *
     * @param App\Http\Requests\Auth\ApiLoginRequest;  $request
     * @return \Illuminate\Http\Response
     */
    public function login(ApiLoginRequest $request)
    {
        $data = $request->validated();
        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $result['uuid'] = $user->uuid;
            $result['token'] = 'Bearer ' . $user->createToken('App')->accessToken;
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => 'Logged in successfully',
                    'content' => new AuthResource($result),
                ], res_code(200)
            );
        }

        return response()->json(
            [
                'type' => res_type('error'),
                'title' => 'Wrong username or password!',
                'content' => res_content('empty'),
            ], res_code(401)
        );
    }

    /**
     * Handle a registration user
     *
     * @param  App\Http\Requests\Auth\ApiRegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(ApiRegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->createNewUser($data);

        $result['uuid'] = $user->uuid;
        $result['token'] = 'Bearer ' . $user->createToken('App')->accessToken;

        if ($user) {
            return response()->json(
                [
                    'type' => res_type('success'),
                    'title' => 'Successful account registration',
                    'content' => new AuthResource($result),
                ], res_code(200)
            );
        }

        return defaultReponseError();
    }

    /**
     * Handle a create user
     *
     * @param  $user
     * @return $user instance
     */
    public function createNewUser($data)
    {
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->uuid = uuid();
        $user->save();

        return $user;
    }
}
