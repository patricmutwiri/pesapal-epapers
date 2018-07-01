<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newspaper;

class NewspaperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $papers = Newspaper::orderByDesc('created_at')->get();
        return view('papers')->with(['papers'=>$papers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('newpaper');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $validatedData = $request->validate([
            'name'  => 'required|max:50',
            'file'  => 'required|mimes:jpeg,png,jpg,zip,pdf,doc,docx,epub,rtf,txt|max:2048',
            'price' => 'required|numeric',
        ]);
        $paper = new Newspaper;
        //upload file
        if ($request->hasFile('file')) {
            $file = $request->file;
            $uniquepaperName = time() . $request->file->getClientOriginalName();
            $request->file->move(public_path('newspapers/'), $uniquepaperName);
            $file = $uniquepaperName;
        }
        $paper->name    = ucwords(strtolower($request->name));
        $paper->price   = $request->price;
        $paper->file    = $file;
        if($paper->save()) {
            return back()->with('message','Newspaper Saved Successfully');
        } else {
            return back()->with('error','Newspaper Saved Successfully');
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
        $paper = Newspaper::find($id);
        if(empty($paper)){
            $paper = 0;
        }
        return view('paper')->with(['paper' => $paper]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
