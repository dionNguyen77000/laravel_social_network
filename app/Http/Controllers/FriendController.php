<?php

namespace Social_Net\Http\Controllers;

use Illuminate\Http\Request;
use Social_Net\Models\User;
use Social_Net\Http\Requests;
use Auth;

class FriendController extends Controller
{
    public function getIndex()
    {
        $friends = Auth::user()->friends();
        $request = Auth::user()->friendRequests();
        return view('friends.index')
            ->with('friends', $friends)
            ->with('requests', $request);
    }

    public function getAdd($username)
    {
        $user = User::where('username', $username)->first();
        if(!$user){
            return redirect()->route('home')->with('info','That user could not be found');
        }

        /*if user send friend request to themselve*/
        if (Auth::user()->id === $user->id){
            return redirect()->route('home');
        }

        /*check if $user has pending request from Auth::user or if Auth::user has pending request from $user */
        if(Auth::user()->hasFriendRequestPending($user)|| $user->hasFriendRequestPending(Auth::user())){
            return redirect()->route('profile.index',['username'=> $user->username])
                ->with('info', 'Friend request already pending');
        }

        /*chek if Auth::user and $user are already friends*/
        if(Auth::user()->isFriendsWith($user)){
            return redirect()->route('profile.index',['username'=> $user->username])
                ->with('info', 'Cannot request because you and '  .  $user->getNameOrUsername() .' are already friends');
        }

        Auth::user()->addFriend($user);
        return redirect()
            -> route('profile.index',['username'=> $username])
            ->with('info', ' Friend request sent.');
    }

    public function getAccept($username)
    {
        $user = User::where('username', $username)->first();
        if(!$user){
            return redirect()->route('home')->with('info','That user could not be found');
        }
        if (!Auth::user()->hasFriendRequestRecieved($user)){
            return redirect()->route('home');
        }

        Auth::user()->acceptFriendRequest($user);

        return redirect()->route('profile.index', ['username'=>$username])
            ->with('info','Friend request accepted.');
    }


}
