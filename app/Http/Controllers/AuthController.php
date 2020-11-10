<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Symphony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;

class AuthController
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $scope = $request->input('scope');

            $token = $user->createToken($scope, [$scope])->accessToken;

            return ['token' => $token];
        }

        return response([
            'error' => 'Invalid credentials'
        ], 401);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->only('first_name', 'last_name', 'email') + [
            'password' => Hash::make($request->input('password')),
            'role_id' => 1,
            'is_influencer' => 1
        ]);

        return response($user, 201);
    }

    public function user()
    {
        $user = \Auth::User();

        $resource = new UserResource($user);

        if ($user->isInfluencer()) {
            return $resource;
        }

        return (new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]);
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = \Auth::User();

        $user->update($request->only('first_name', 'last_name', 'email'));

        return response(new UserResource($user), 202);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {

        $user = \Auth::User();

        $user->update(['password' => Hash::make($request->input('password'))]);

        return response(new UserResource($user), 202);
    }
}
