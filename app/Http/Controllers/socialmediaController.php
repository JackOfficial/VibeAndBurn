<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\socialmedia;

class socialmediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Using simplePaginate(15) is often better for performance as your list grows
        $socialmedia = socialmedia::orderBy('socialmedia', 'ASC')->get();
        $socialmediaCounter = socialmedia::count();
        
        return view('admin.social-media.index', compact('socialmedia', 'socialmediaCounter')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('admin.social-media.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // unique:table,column - prevents duplicate platforms
            'socialmedia' => ['required', 'string', 'max:255', 'unique:social_media,socialmedia']
        ]);

        $created = socialmedia::create([
            'socialmedia' => $request->input('socialmedia')
        ]);

        if($created) {
            return redirect()->route('admin.socialmedia.index')
                ->with('addSocialmediaSuccess', 'Social Media has been added successfully');
        }

        return redirect()->back()
            ->withInput()
            ->with('addsocialmediaFail', 'Social Media could not be added');
    }

    /**
     * Show the form for editing the specified resource.
     * Route Model Binding: Laravel automatically finds the record by ID
     */
    public function edit(socialmedia $socialmedia)
    {
        return view('admin.social-media.edit', compact('socialmedia')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, socialmedia $socialmedia)
    {
        $request->validate([
            // Ignore current ID during unique check so you can save without changes
            'socialmedia' => ['required', 'string', 'max:255', 'unique:social_media,socialmedia,' . $socialmedia->id]
        ]);

        $updated = $socialmedia->update([
            'socialmedia' => $request->input('socialmedia')
        ]);

        if($updated) {
            return redirect()->route('admin.socialmedia.index')
                ->with('updateSocialmediaSuccess', 'Social media has been updated successfully');
        }

        return redirect()->back()
            ->with('updateSocialmediaFail', 'Social media could not be updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(socialmedia $socialmedia)
    {
        if($socialmedia->delete()) {
            return redirect()->back()
                ->with('deleteSocialmediaSuccess', 'Social media was deleted successfully');
        }

        return redirect()->back()
            ->with('deleteSocialmediaFail', 'Social media could not be deleted');
    }
}