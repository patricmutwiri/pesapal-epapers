<?php

namespace App\Http\Controllers;

use Pesapal;
use Mail;
use Illuminate\Http\Request;
use App\Orders;
use App\Newspaper;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function payment(Request $request){//initiates payment
        $papers     = $request->papers;
        if(!Auth::check()) {
            return redirect(route('papers'))->with(['message' => 'Please login/register first to checkout']);
        }
        if(empty($papers)) {
            return redirect(route('papers'))->with(['message' => 'Please select a paper first']);
        }
        $total      = array();
        $newspapers = array();
        $paperids   = array();
        foreach ($papers as $key => $paper) {
            $paper          = json_decode($paper,true);
            $total[]        = $paper['price'];
            $thispaper      = Newspaper::find($paper['id']);
            $papername      = 'Paper: '.ucwords($thispaper->name).'/Dated: '.date('d-m-Y',strtotime($thispaper->created_at));
            $newspapers[]   = $papername;
            $paperids[]     = $paper['id'];
        }

        //dd($total);
        $amount = array_sum($total);
        $payments = new Orders;
        $payments->businessid       = '000'.Auth::guard('business')->id(); //Business ID
        $payments->transactionid    = Pesapal::random_reference();
        $payments->status   = 'NEW'; //if user gets to iframe then exits, i prefer to have that as a new/lost transaction, not pending
        $payments->amount   = $amount;
        $payments->total    = $amount;
        $payments->uid      = Auth::user()->id;
        $payments->dropoff  = $request->dropoff;
        $payments->phonenumber  = $request->phonenumber;
        $payments->papers       = implode(',', $newspapers);
        $payments->description  = 'Payment for; '.implode(',', $newspapers);
        $payments->paperids     = implode(',', $paperids);

        $payments->save();

        $details = array(
            'amount'        =>  $payments->amount,
            'description'   =>  implode(',', $newspapers),
            'type'          =>  'MERCHANT',
            'first_name'    =>  $request->firstname,
            'last_name'     =>  $request->lastname,
            'email'         =>  $request->email,
            'phonenumber'   =>  $request->phonenumber,
            'reference'     =>  $payments->transactionid,
            'height'        =>  '400px',
            'currency'      => config('pesapal.currency')
        );
        $iframe=Pesapal::makePayment($details);
        return view('payments.paynow', compact('iframe'));
    }
    public function paymentsuccess(Request $request)//just tells u payment has gone thru..but not confirmed
    {
        $trackingid = $request->input('tracking_id');
        $ref = $request->input('merchant_reference');

        $payments = Orders::where('transactionid',$ref)->first();
        $payments->trackingid = $trackingid;
        $payments->status = 'PENDING';
        $payments->save();
        //go back home
        $all = Orders::all();

        $contactEmail   = Auth::user($payments->uid)->email;
        $contactName    = Auth::user($payments->uid)->name;

        Mail::raw('Hello '.$contactName.', your order was placed and the status assigned as PENDING. We will update you as it is processed. ', function ($message) use ($contactEmail, $contactName) {
           $message->to($contactEmail,$contactName)->subject('Order Notification');
        });

        return view('payments.order', compact('payments'));
    }
    //This method just tells u that there is a change in pesapal for your transaction..
    //u need to now query status..retrieve the change...CANCELLED? CONFIRMED?
    public function paymentconfirmation(Request $request)
    {
        $trackingid                 = $request->input('pesapal_transaction_tracking_id');
        $merchant_reference         = $request->input('pesapal_merchant_reference');
        $pesapal_notification_type  = $request->input('pesapal_notification_type');

        //use the above to retrieve payment status now..
        $this->checkpaymentstatus($trackingid,$merchant_reference,$pesapal_notification_type);
    }
    //Confirm status of transaction and update the DB
    public function checkpaymentstatus($trackingid,$merchant_reference,$pesapal_notification_type){
        $status     = Pesapal::getMerchantStatus($merchant_reference);
        $payments   = Orders::where('trackingid',$trackingid)->first();
        $payments->status = $status;
        $payments->payment_method = "PESAPAL";//use the actual method though...
        $payments->save();
        // send mail here
        $contactEmail   = Auth::user($payments->uid)->email;
        $contactName    = Auth::user($payments->uid)->name;

        Mail::raw('Hello '.$contactName.', your order was received and the status assigned as '.$status.'', function ($message) use ($contactEmail, $contactName) {
           $message->to($contactEmail,$contactName)->subject('Order Notification');
        });

        $this->confirmation($trackingid,$status,'PESAPAL',$merchant_reference);
        return "success";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()) {
            $orders = Orders::where('uid',Auth::user()->id)->get();
            return view('payments.orders',compact('orders'));
        } else {
            return redirect('home')->with('message','Login first to view orders');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Orders::find($id);
    }
    
    public function confirmation($trackingid,$status,$payment_method,$merchant_reference)
    {
        $payments = Orders::where('trackingid',$trackingid)->first();
        $payments->payment_status = $status;
        $payments->status = $status;
        $payments->payment_method = $payment_method;
        $payments->save();

          // send mail here
        $contactEmail   = Auth::user($payments->uid)->email;
        $contactName    = Auth::user($payments->uid)->name;

        Mail::raw('Hello '.$contactName.', your order status was updated to '.$status.'', function ($message) use ($contactEmail, $contactName) {
           $message->to($contactEmail,$contactName)->subject('Order Notification');
        });

        if($status == 'COMPLETED' || $status == 'completed') {
            $epapers = explode(',', $payments->paperids);
            $files = array();
            foreach ($epapers as $key => $epaper) {
                $thispaper      = Newspaper::find($epaper);
                $files[]        = public_path('newspapers/').$thispaper->file;
            }
            // send paper here
            Mail::send(['html' => 'emails.neworder'], ['files' => $files, 'payment' => $payments], function($message) use ($contactEmail, $contactName){
                foreach ($files as $key => $file) {
                    $message->attach($file, [
                        'as' => time().'paper.pdf',
                        'mime' => 'application/pdf',
                    ]);
                }
                $message->to($contactEmail, $contactName)->subject('Order Completed');
            });
        }   
    }
}
