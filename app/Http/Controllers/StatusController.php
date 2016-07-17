<?php

namespace Social_Net\Http\Controllers;

use Illuminate\Http\Request;

use Social_Net\Http\Requests;
use Social_Net\Models\Status;
use Auth;

class StatusController extends Controller
{
    public function postStatus(Request $request){
        $this->validate($request, [
           'status'=> 'required|max:100',
        ]);

        Auth::user()->statuses()->create([
            'body'=>$request->input('status'),
        ]);
        return redirect()->route('home')->with('info','Status posted');
    }

    public function postReply(Request $request, $statusId)
    {
        $this->validate($request,[
           "reply-{$statusId}" => 'required|max:100',
        ], [
            'required' => 'The reply is required.'
        ]);
        // find the status (not reply) we need to reply to
        $status = Status::notReply()->find($statusId);

        // id the status doesnot exit in table
        if(!$status){
            return redirect()->route('home');
        }
        // if I try to reply status from person who isnot my friend
        if(!Auth::user()->isFriendswith($status->user) && Auth::user()->id !== $status->user->id){
            return redirect()->route('home');
        }
        /*$reply = Status::create([
             'body' => $request->input("reply-{$statusId}"),
        ])-> user() : set user_id: who is the author of reply
        ->associate(Auth::user()): set parent_id (the foreign key for the author of status
         * Status belongs to user so method associate will set the foreign key on the child model:*/
        $reply = Status::create([
             'body' => $request->input("reply-{$statusId}"),
        ])-> user()->associate(Auth::user());

        $status->replies()->save($reply);
        return redirect()->back();
        
    }
}
