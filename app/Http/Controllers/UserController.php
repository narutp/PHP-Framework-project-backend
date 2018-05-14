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
                'type' => $request->get('type'),
                'facebook' => $request->get('facebook'),
                'phone_number' => $request->get('phone_number')
            ]);
            return $user;
        }
        return ['message' => 'Require admin access to create a user'];
    }
 
    public function indexSubordinates(Request $request) { 
        return User::where('supervisor_id', $request->user()->id)->get();
    }

    public function indexColleague(Request $request) {
        return User::where('supervisor_id', $request->user()->supervisor_id)->where('id', '!=', $request->user()->id)->get();
    }
    
    public function update(Request $request)
    {
    //   $user->update($request->only(['name', 'address', 'facebook', 'phone_number']));

    //   return $user;

        $user = $request->user()->update($request->only(['name', 'address', 'facebook', 'phone_number']));

        return response()->json($user, 200);
    }

    public function subordinateTask(Request $request)
    {
        $users = $request->user()->subordinates()->with('tasks.user')->get();
        $tasks = [];
        foreach ($users as $user) {
            foreach ($user->tasks as $task) {
                array_push($tasks, $task);
            }
        }
        return $tasks;
    }
    
    public function setRole(Request $request) {
        if ($request->user()->is_admin) {
            $user = User::where(['id' => $request->get('id')])->first();
            $user->update($request->only(['type']));
            return $user;
        }
        return ['message' => 'Require admin access to set role'];
    }

    public function setHierarchy(Request $request) {
        if ($request->user()->is_admin) {
            $user = User::where(['id' => $request->get('user_id')])->first();
            $user->update($request->only(['supervisor_id']));
            return $user;
        }
        return ['message' => 'Require admin access to set hierarchy'];    
    }

    public function show(User $user) {
        return $user;
    }

    public function setDepartment(Request $request) {
        if ($request->user()->is_admin) {
            $user = User::where(['id' => $request->get('id')])->first();
            $user->update($request->only(['department']));
            return $user;
        }
        return ['message' => 'Require admin access to set a department'];
    }
}

