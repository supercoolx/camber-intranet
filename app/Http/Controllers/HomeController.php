<?php

namespace App\Http\Controllers;

use App\Helpers\HelperString;
use Illuminate\Http\Request;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('/home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        if ( Auth::user()->isAdmin() ) {
            return redirect('/admin/dashboard');
        }

        $orders = Auth::user()->orders()->get();

        $assistants = User::whereHas('roles', function($q){
            $q->where('name', 'assistant');
        })->get();
        
        $user = Auth::user()->roles()
            ->where('roles.name', 'agent')->first();

        $camAccount = $referrerLink = '';

        if( is_object($user) ) {
            $camAccount = Auth::user()->roles()->where('roles.name', 'agent')->first()->pivot->cam_account;

            $referrerLink = Auth::user()->roles()->where('roles.name', 'agent')->first()->pivot->referrer_link;
        }

        return view('home', compact('orders','assistants', 'camAccount', 'referrerLink'));
    }
}
