<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advert;

class AdvertsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $adverts = Advert::orderBy('id', 'DESC')->get();
       return view('admin.adverts.index', compact('adverts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.add.advert');
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
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
            'advert' => 'required',
           ]);
 
           $image_path = null;
           if($request->hasFile('photo')){
              $image_path = $request->file('photo')->store('adverts', 'public'); 
           }
           
           Advert::where('status', 1)->update([
               'status' => 0
               ]);

           $advert = Advert::create([
            'photo' => $image_path,
            'advert' => $request->input('advert'),
        ]);

        if($advert){
            return redirect('advert')->with('advertSuccess', 'Advert added successfully');
        } 
        else{
          return redirect()->back()->with('advertFail', 'Advert failed to post');
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
    public function edit($id)
    {
        $ad = Advert::findOrFail($id);
        return view('admin.edit.advert', compact('ad'));
        
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
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
            'advert' => 'required',
            'status' => 'required'
           ]);
 
           $image_path = null;
           if($request->hasFile('photo')){
              $image_path = $request->file('photo')->store('adverts', 'public'); 
           }
           
           $hide = Advert::where('status', 1)->update([
               'status' => 0
               ]);

            if($hide){
             $advert = Advert::where('id', $id)->update([
            'photo' => $image_path,
            'advert' => $request->input('advert'),
            'status' => $request->input('status'),
        ]);   
            }

        if($advert){
            return redirect('advert')->with('advertSuccess', 'Advert updated successfully');
        } 
        else{
          return redirect()->back()->with('advertFail', 'Advert failed to update');
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
       $advert = Advert::where('id', $id)->delete();
       if($advert){
           return redirect('advert')->with('advertSuccess', 'Advert delete successfully'); 
       }
       else{
           return redirect()->back()->with('advertFail', 'Advert failed to delete'); 
       }
    }
}
