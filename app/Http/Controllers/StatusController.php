<?php

namespace Social_Net\Http\Controllers;

use Illuminate\Http\Request;

use Social_Net\Http\Requests;
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
}
