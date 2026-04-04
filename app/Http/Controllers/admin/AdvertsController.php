<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advert;
use Illuminate\Support\Facades\Storage;

class AdvertsController extends Controller
{
    public function index()
    {
        $adverts = Advert::latest('id')->get();
        return view('admin.adverts.index', compact('adverts'));
    }

    public function create()
    {
        return view('admin.adverts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'advert' => ['required', 'string'],
        ]);

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('adverts', 'public');
        }

        // Deactivate all other adverts if this logic is for "one active at a time"
        Advert::where('status', 1)->update(['status' => 0]);

        $advert = Advert::create([
            'photo'  => $imagePath,
            'advert' => $request->input('advert'),
            'status' => 1, // Default to active
        ]);

        return $advert 
            ? redirect('advert')->with('advertSuccess', 'Advert added successfully')
            : redirect()->back()->with('advertFail', 'Failed to save advert');
    }

    public function edit($id)
    {
        $ad = Advert::findOrFail($id);
        return view('admin.adverts.edit', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $advert = Advert::findOrFail($id);

        $request->validate([
            'photo'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'advert' => ['required', 'string'],
            'status' => ['required', 'boolean']
        ]);

        $data = $request->only(['advert', 'status']);

        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($advert->photo) {
                Storage::disk('public')->delete($advert->photo);
            }
            $data['photo'] = $request->file('photo')->store('adverts', 'public');
        }

        // If this advert is being set to active, deactivate others
        if ($request->status == 1) {
            Advert::where('id', '!=', $id)->update(['status' => 0]);
        }

        $updated = $advert->update($data);

        return $updated
            ? redirect()->route('admin.advert.index')->with('advertSuccess', 'Advert updated successfully')
            : redirect()->back()->with('advertFail', 'Failed to update advert');
    }

    public function destroy($id)
    {
        $advert = Advert::findOrFail($id);
        
        // Delete photo from storage before deleting record
        if ($advert->photo) {
            Storage::disk('public')->delete($advert->photo);
        }

        return $advert->delete()
            ? redirect('advert')->with('advertSuccess', 'Advert deleted successfully')
            : redirect()->back()->with('advertFail', 'Failed to delete advert');
    }
}