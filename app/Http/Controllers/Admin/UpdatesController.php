<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Updates;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UpdatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.updates.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.updates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'vibeupdate' => 'required|string'
        ]);
            
        // Note: Make sure 'vibeUpdate' matches your migration casing choice perfectly!
        $update = Updates::create([
            'vibeUpdate' => $request->vibeupdate
        ]);
            
        if ($update) {
            return redirect()->back()->with('updateSuccess', 'Update has been posted successfully');
        }
        
        return redirect()->back()->with('updateFail', 'Update failed');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        dd("Show", $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        // <-- FIX: Form rendering for editing belongs here, not in update()
        $update = Updates::findOrFail($id);
        return view('admin.updates.create', compact('update'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // <-- FIX: This method should handle saving the changes submitted by the edit form
        $request->validate([
            'vibeupdate' => 'required|string'
        ]);

        $update = Updates::findOrFail($id);
        $status = $update->update([
            'vibeUpdate' => $request->vibeupdate
        ]);

        if ($status) {
            return redirect()->back()->with('updateSuccess', 'Update modified successfully');
        }

        return redirect()->back()->with('updateFail', 'Failed to save changes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        // Using findOrFail and calling delete() directly on the object triggers model events cleanly
        $update = Updates::findOrFail($id);
           
        if ($update->delete()) {
            return redirect()->back()->with('updateSuccess', 'Deleted successfully');
        }

        return redirect()->back()->with('updateFail', 'Delete failed');
    }
}