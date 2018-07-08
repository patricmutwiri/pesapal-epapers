<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newspaper;
use App\Orders;
use Auth;
use Storage;
use File;
use Response;

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
        if(Auth::check()) {
            $uid = Auth::user()->id;
            $orders = Orders::where('uid',$uid)->OrderByDesc('id')->get();
            foreach ($orders as $key => $order) {
                if(!empty($order->paperids)):
                    $mypapers = explode(',', $order->paperids);
                    foreach ($mypapers as $key => $mypaper) {
                        $thispaper  = Newspaper::find($mypaper);
                        $orders[$key]['mypapers']    = array(
                            'papername' => $thispaper['name'], //.' / '.date('d-M-Y',strtotime($thispaper['created_at'])), 
                            'path'      => $thispaper['id']
                        );
                    }
                endif;
            }
        }
        return view('home')->with(
            [
                'papers' => $papers, 
                'orders' => $orders
            ]
        );
    }

    /*
    * get pdf file
    * display/download
    */
    // Route::get('file/{path}', 'HomeController@getFile');

    public function getFile($id)
    {

        $thispaper  = Newspaper::findOrFail($id);

        $filePath = public_path('newspapers/').$thispaper->file;
        // file not found
        if(!File::exists($filePath)) {
          abort(404, 'Paper not found, file uploaded incorrectly');
        }

        return response()->file($filePath);
    }

}
