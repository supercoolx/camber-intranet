<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
// use App\Algorithm;
// use App\Coin;
// use App\DualMiningPair;
// use App\Advertising;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index');
    }

}
