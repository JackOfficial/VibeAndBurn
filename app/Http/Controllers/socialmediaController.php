<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\socialmedia;
//use App\Models\admin;

class socialmediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->session()->has('adminName')){
            
             $name = $request->Session()->get('adminName');
             //admin::where('id', session()->get(''))->get();
        $socialmedia = socialmedia::orderBy('socialmedia', 'ASC')->get();
        $socialmediaCounter = socialmedia::count();
       return view('admin.manage.socialmedia', compact('name', 'socialmedia', 'socialmediaCounter'));
        }
        else{
            return view('auth.admin-login'); 
        }
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->session()->has('adminName')){
            
             $name = $request->Session()->get('adminName');
        return view('admin.add.socialmedia', compact('name'));
        }
         else{
            return view('auth.admin-login'); 
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'socialmedia' => ['required', 'string', 'max:255']
            ]);

           $socialmedia = socialmedia::create([
                'socialmedia'=>$request->input('socialmedia')
                ]);

                if($socialmedia){
                return redirect()->back()->with('addSocialmediaSuccess','Social Media has been added successfully');
                }
                else{
                    return redirect()->back()->with('addsocialmediaFail','Social Media could not added');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->session()->has('adminName')){
            
             $name = $request->Session()->get('adminName');
             
             $socialmedia = socialmedia::findOrFail($id);
        return view('admin.edit.socialmedia', compact('socialmedia'));
        }
         else{
            return view('auth.admin-login'); 
        }
        
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
        $request->validate([
            'socialmedia' => ['required', 'string', 'max:255']
            ]);

            $socialmedia = socialmedia::where('id', $id)->update([
                'socialmedia'=>$request->input('socialmedia')
        ]);

        if($socialmedia){
            return redirect()->back()->with('updateSocialmediaSuccess','Social media has been updated successfully');
            }
            else{
                return redirect()->back()->with('updateSocialmediaFail','Social media could not updated');
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
        $socialmedia = socialmedia::where('id',$id)->delete();

        if($socialmedia){
            return redirect()->back()->with('deleteSocialmediaSuccess', 'Social media was deleted successfully');
        }
        else{
            return redirect()->back()->with('deleteSocialmediaFail', 'Social media could not be deleted');
        }
    }
}
