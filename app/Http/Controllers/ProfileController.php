<?php

namespace Social_Net\Http\Controllers;

use Illuminate\Http\Request;
use Social_Net\Models\User;
use Social_Net\Http\Requests;
use Auth;


class ProfileController extends Controller
{
    public function getProfile($username)
    {
        /*get the user pass from url*/
        $user = User :: where('username', $username) -> first();
        if(!$user){
            abort(404);
        }

        $statuses = $user->statuses()->notReply()->get();

        return view('profile.index')
            ->with('user', $user)
            ->with('statuses' , $statuses)
            ->with('authUserIsFriend' , Auth::user()->isFriendsWith($user));
    }

    public function getEdit()
    {
        return view('profile.edit');
    }
    public function postEdit(Request $request)
    {
        $this ->validate($request, [
           'first_name' => 'required|alpha|max:50',
            'last_name' => 'required|alpha|max:50',
            'location'  => 'max:20',
        ]);

        Auth::user()->update([
           'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'location' => $request->input('location'),
        ]);

        return redirect()->route('profile.edit')->with('info', 'Your profile has been updated');
    }

}
