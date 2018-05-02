<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use App\User as User;

class UserController extends Controller
{
    public function index(Request $request) 
    {
        $type = $request->get('type');
        $type = strtolower($type);
        if ($type === 'admin') {
            $users = User::where('is_admin', true)->get();
        } else if ($type === 'supervisor') {
            $users = User::where('type', 'supervisor')->get();
        } else if ($type === 'subordinate') {
            $users = User::where('type', 'subordinate')->get();
        } else {
            $users = User::get();
        }

        return $users;
    }

    public function store(Request $request) {
        if (User::where(['email' => $request->get('email')])->first()) {
            return ['message' => 'email is already used'];
        }
        // return $request->user();
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
 
    public function indexSubordinates(Request $request) { 
        return User::where('supervisor_id', $request->user()->id)->get();
    }

}
