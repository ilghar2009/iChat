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

    public function authenticate(Request $request)
    {
        $validator = validator()->make($request->all(),[
           'name' => ['sometimes', 'string', 'max:10', 'unique:users,name'],
           'username' => ['required', 'string', 'max:10', 'unique:users,username'],
           'password' => ['required', 'string', 'min:8'],
        ]);

        if($request->role === 'sign') {
            if(!$validator->fails()) {
                $user = User::create($request->all());
                Auth::login($user);
                session()->regenerate();

                return redirect()->route('chat.index');
            }else
                return redirect()->route('authP')
                    ->withErrors('messages', $validator->errors()->messages())
                    ->withInput();
        } else {
            if (!$validator->fails()) {
                $userName = $request->user_name;

                $user = User::where('user_name', $userName)->first();
                if ($user and Hash::check($request->password, $user->password)) {
                    Auth::login($user);
                    session()->regenerate();

                    return redirect()->route('chat.index');
                } else
                    return view('user.auth', ['alertL' => 'username or password is wrong']);
            }else
                return redirect()->route('authP')
                    ->withErrors('messages', $validator->errors()->messages())
                    ->withInput();
        }

        return redirect()->route('authP');
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
