<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Updates;

class UpdatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('admin.manage.updates');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.add.update');
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
            'vibeupdate' => 'required|string'
            ]);
            
       $update = Updates::create([
           'vibeUpdate' => $request->vibeupdate
           ]);
           
           if($update){
               return redirect()->back()->with('updateSuccess', 'Update has been posted successfully');
           }
           else{  
                return redirect()->back()->with('updateFail', 'Update failed');
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
      dd("Show");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          dd("Edit");
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
        $update = Updates::findOrFail($id);
        return view('admin.add.update', compact('update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $update = Updates::where('id', $id)->delete();
           
           if($update){
               return redirect()->back()->with('updateSuccess', 'Deleted successfully');
           }
           else{
                return redirect()->back()->with('updateFail', 'delete failed');
           }
    }
}
