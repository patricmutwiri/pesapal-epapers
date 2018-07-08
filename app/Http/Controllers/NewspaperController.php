<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newspaper;
use File;
use Storage;

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
            $uniquepaperName = uniqid().$request->file->getClientOriginalName();
            $request->file->move(public_path('newspapers/'), $uniquepaperName);
        }
        $paper->name    = ucwords(strtolower($request->name));
        $paper->price   = $request->price;
        $paper->file    = $uniquepaperName;
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
        $paper = Newspaper::findOrFail($id);
        //dd($paper);
        return view('editnewspaper', compact('paper'));
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
        $paper = Newspaper::findOrFail($id);
        //upload file
        if ($request->hasFile('file')) {
            $file = $request->file;
            $uniquepaperName = uniqid().$request->file->getClientOriginalName();
            $request->file->move(public_path('newspapers/'), $uniquepaperName);
            $paper->file    = $uniquepaperName;
        }
        $paper->name    = ucwords(strtolower($request->name));
        $paper->price   = $request->price;
        if($paper->save()) {
            return back()->with('message','Newspaper updated Successfully');
        } else {
            return back()->with('error','Newspaper updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paper = Newspaper::find($id);
    }
}
