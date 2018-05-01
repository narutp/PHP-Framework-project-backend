<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use App\User as User;

class UserController extends Controller
{
    public function store(Request $request) {
        if (User::where(['email' => $request->get('email')])->first()) {
            return ['message' => 'email is already used'];
        }
        if ($request->user()->is_admin) {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'lineid' => $request->get('lineid'),
                'profile_picture' => $request->get('profile_picture') or null,
                'address' => $request->get('address'),
                'is_admin' => $request->get('is_admin') === 'true',
                'type' => $request->get('type')
            ]);
            return $user;
        }
        return ['message' => 'Require admin access to create a user'];
    }
}
