<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\UserRole;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symphony\Component\HttpFoundation\Response;

class UserController
{
    public function index()
    {
        Gate::authorize('view', 'users');

        $users = User::paginate();

        return UserResource::collection($users);
    }

    public function show($id)
    {
        Gate::authorize('view', 'users');

        $user = User::find($id);

        return new UserResource($user);
    }

    public function store(UserCreateRequest $request)
    {
        Gate::authorize('edit', 'users');

        $user = User::create($request->only('first_name', 'last_name', 'email', 'role_id') + [
            'password' => Hash::make(1234),
        ]);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id')
        ]);

        return response(new UserResource($user), 201);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        Gate::authorize('edit', 'users');

        $user = User::find($id);

        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        UserRole::where('user_id', $user->id)->delete();

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id')
        ]);

        return response(new UserResource($user), 202);
    }

    public function destroy($id)
    {
        Gate::authorize('edit', 'users');

        User::destroy($id);

        return response(null, 204);
    }
}
