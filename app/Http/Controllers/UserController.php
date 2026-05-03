<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function sign(){
        return view('user.auth');
    }

    public function authenticate(Request $request){
        validator($request->all())->validate();

        if($request->role === 'sign') {
            $valid = User::where('name', $request->name)
                ->orWhere('user_name', $request->user_name)->first();

            if($valid)
                return view('user.auth', ['alertS' => 'name or username already exists']);
            else {
                $user = User::create($request->all());
                Auth::login($user);
                session()->regenerate();

            }
        }else{
            $userName = $request->user_name;

            $user = User::where('user_name', $userName)->first();
            if($user and Hash::check($request->password, $user->password)){
                Auth::login($user);
                session()->regenerate();

                return redirect()->route('chat.index');
            }else
                return view('user.auth', ['alertL' => 'username or password is wrong']);
        }
    }

    public function access_change(User $user){
        $role = 0;

        if($user->user_id != Auth::user()->user_id && $user->user_name && 'Admin') {
            $user->is_access = !$user->is_access;
            $user->save();
            $role = 1;
        }

        return redirect()->route('user.index', ['success' => $role]);
    }

    public function role_change(User $user){
        $role = 0;

        if($user->user_id != Auth::user()->user_id && $user->user_name && 'Admin') {
            $user->is_admin = !$user->is_admin;
            $user->save();
        }

        return redirect()->route('user.index', ['success' => $role]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back();
    }
}
