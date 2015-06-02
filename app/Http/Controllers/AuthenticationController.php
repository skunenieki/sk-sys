<?php

namespace Skunenieki\System\Http\Controllers;

use JWT;
use Auth;
use Illuminate\Http\Request;
use Skunenieki\System\Models\User;

class AuthenticationController extends Controller
{
    public function authenticate(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->createToken($request->user());
        }

        return response(['error_msg' => 'Bad Credentials'], 401);
    }

    protected function createToken($user)
    {
        $expiresIn = 60 * 60 * 10; // 10 hours

        $token = [
            'aud' => $user->id,
            'iat'    => time(),
            'exp'    => time() + $expiresIn,
            'jti'    => uniqid(),
        ];

        return [
            'access_token' => JWT::encode($token, 'MySecretKey'),
            'type'         => 'bearer',
            'expires_in'   => $expiresIn,
        ];
    }
}
