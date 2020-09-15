<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symphony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index() {
        return user::all();
    }

    public function show($id) {
        return User::find($id);
    }

    public function store(Request $request) {
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response($user, 201);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);

        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response($user, 202);
    }

    public function destroy($id) {
        User::destroy($id);

        return response(null, 204);
    }
}
