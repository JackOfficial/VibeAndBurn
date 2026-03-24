<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terms;

class TermsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms = Terms::firstOrFail();
        return view('admin.manage.terms', compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $terms = Terms::findOrFail($id);
        return view('admin.edit.terms', compact('terms'));
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
        $terms = $request->validate([
            'terms' => 'required|string'
            ]);
            
        $update_terms = Terms::where('id', $id)->update($terms);
        
        if($update_terms){
            return redirect()->route('terms.index')->with('updateTermsSuccess', 'Terms has been updated successfully');
        }
        else{
             return back()->with('updateTermsFail', 'Terms could not be updated');
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
        $delete_terms = Terms::where('id', $id)->delete();
        
        if($delete_terms){
            return back()->with('deleteTermsSuccess', 'Terms and conditions has been deleted');
        }
        else{
             return back()->with('deleteTermsFail', 'Terms could not be deleted');
        }
    }
}
