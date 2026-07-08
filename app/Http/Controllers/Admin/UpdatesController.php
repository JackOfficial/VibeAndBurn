<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Updates;

class UpdatesController extends Controller
{
    
    public function index()
    {
       return view('admin.updates.index');
    }

    
    public function create()
    {
       return view('admin.updates.create');
    }

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

    public function show($id)
    {
      dd("Show");
    }

    public function edit($id)
    {
          dd("Edit");
    }

    public function update(Request $request, $id)
    {
        $update = Updates::findOrFail($id);
        return view('admin.updates.create', compact('update'));
    }

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
