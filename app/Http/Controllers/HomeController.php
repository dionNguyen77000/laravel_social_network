<?php

namespace Social_Net\Http\Controllers;
use Auth;
use Social_Net\Models\Status;

class HomeController extends Controller{
    /**
     * @return string
     */
    public function index()
    {
        if (Auth::check()){
            $statuses = Status::where(function($query){
                /*callback function that return posts written by Auth::user
                or the posts written by friends of Auth::user*/
                return $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('user_id', Auth::user()->friends()->lists('id'));
            })
            ->orderBy('created_at', 'desc')->paginate(2);


            return view('timeline.index')->with('statuses', $statuses);
        }
        return view('home');
    }
}

