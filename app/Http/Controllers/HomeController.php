<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newspaper;
use App\Orders;
use Auth;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $papers = Newspaper::all();
        $orders = null;
        if(Auth::user()) {
            $uid = Auth::user()->id;
            $orders = Orders::where('uid',$uid);
        }
        return view('home')->with(
            [
                'papers' => $papers, 
                'orders' => $orders
            ]
        );
    }
}
