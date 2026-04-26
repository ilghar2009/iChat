<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            }
        }else{
            $userName = $request->user_name;
            $userPass = $request->password;

            $user = User::where('user_name', $userName)->where('password', $userPass)->first();
            if($user){
                Auth::login($user);
            }else
                return view('user.auth', ['alertL' => 'username or password is wrong']);
        }
    }

    public function show(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
