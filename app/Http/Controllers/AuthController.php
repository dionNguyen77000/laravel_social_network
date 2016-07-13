<?php

namespace Social_Net\Http\Controllers;
use Social_Net\Models\User;
use Illuminate\Http\Request;
use Social_Net\Http\Requests;
use Auth;

class AuthController extends Controller
{
    public function  getSignup()
    {
        return view('auth.signup');
    }
    public function  postSignup(Request $request)
    {
        $this->validate($request,[
           'email' => 'required|unique:users|email|max:255',
            'username' => 'required|unique:users|alpha_dash|max:255',
            'password' => 'required|min:6',

        ]);
        User::create([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->route('home')->with('info', 'Your account has been created and you can now sign in');
    }

    public function getSignin()
    {
        return view('auth.signin');
    }

    public function postSignin(Request $request)
    {
        $this->validate( $request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        if (!Auth::attempt($request->only(['email','password']), $request->has('remember'))){
            return redirect()->back()->with('info', 'could not sign in with provided details.');
        }
        return redirect() ->route('home')->with('info', 'You successfully sign in');
    }

    public function getSignout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
