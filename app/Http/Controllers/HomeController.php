<?php

namespace Social_Net\Http\Controllers;

class HomeController extends Controller{
    /**
     * @return string
     */
    public function index()
    {
        return view('home');
    }
}

