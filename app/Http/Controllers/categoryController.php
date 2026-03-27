<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\socialmedia;
use App\Models\category;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = category::join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->select('categories.*', 'socialmedia.socialmedia')
        ->orderBy('categories.category', 'ASC')
        ->get();

        $categoryCounter = category::join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->count();

       return view('admin.manage.categories', compact('categories', 'categoryCounter')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $socialmedia = socialmedia::orderBy('socialmedia', 'ASC')->get();
        return view('admin.add.category', compact('socialmedia')); 
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
            'socialmedia' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            ]);

           $category = category::create([
                'socialmedia_id'=>$request->input('socialmedia'),
                'category'=>$request->input('category'),
                ]);

                if($category){
                return redirect()->back()->with('addCategorySuccess','Category has been added successfully');
                }
                else{
                    return redirect()->back()->with('addCategoryFail','Category could not added');
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
        $category = category::join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->where('categories.id', '=', $id)
       ->select('categories.*', 'socialmedia.socialmedia')
        ->orderBy('categories.category', 'ASC')
        ->get();

        $socialmedia = socialmedia::orderBy('socialmedia', 'ASC')->get();

        return view('admin.edit.category', compact('category', 'socialmedia'));
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
            'socialmedia' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            ]);

           $category = category::where('id', $id)->update([
                'socialmedia_id'=>$request->input('socialmedia'),
                'category'=>$request->input('category'),
                ]);

                if($category){
                return redirect()->back()->with('updateCategorySuccess','Category has been updated successfully');
                }
                else{
                    return redirect()->back()->with('updateCategoryFail','Category could not added');
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
        $category = category::where('id',$id)->delete();

        if($category){
            return redirect()->back()->with('deleteCategorySuccess', 'Category was deleted successfully');
        }
        else{
            return redirect()->back()->with('deleteCategoryFail', 'Category could not be deleted');
        }
    }
}
