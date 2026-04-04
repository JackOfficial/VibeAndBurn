<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\socialmedia as Socialmedia; // Ensure PascalCase if following PSR-4
use App\Models\category as Category;    // Ensure PascalCase

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Use eager loading 'with' instead of manual joins
        $categories = Category::with('socialmedia')
            ->orderBy('category', 'ASC')
            ->get();

        $categoryCounter = $categories->count();

        return view('admin.categories.index', compact('categories', 'categoryCounter')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $socialmedia = Socialmedia::orderBy('socialmedia', 'ASC')->get();
        return view('admin.categories.create', compact('socialmedia')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'socialmedia' => ['required', 'exists:socialmedia,id'], // Ensures the ID actually exists
            'category'    => ['required', 'string', 'max:255'],
        ]);

        $category = Category::create([
            'socialmedia_id' => $validated['socialmedia'],
            'category'       => $validated['category'],
        ]);

        if ($category) {
            return redirect()->back()->with('addCategorySuccess', 'Category has been added successfully');
        }

        return redirect()->back()->with('addCategoryFail', 'Category could not be added');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // findOrFail is safer as it handles missing IDs automatically
        $category = Category::with('socialmedia')->findOrFail($id);
        $socialmedia = Socialmedia::orderBy('socialmedia', 'ASC')->get();

        return view('admin.categories.edit', compact('category', 'socialmedia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'socialmedia' => ['required', 'exists:socialmedia,id'],
            'category'    => ['required', 'string', 'max:255'],
        ]);

        $category = Category::findOrFail($id);
        $status = $category->update([
            'socialmedia_id' => $validated['socialmedia'],
            'category'       => $validated['category'],
        ]);

        if ($status) {
            return redirect()->back()->with('updateCategorySuccess', 'Category has been updated successfully');
        }

        return redirect()->back()->with('updateCategoryFail', 'Category could not be updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->delete()) {
            return redirect()->back()->with('deleteCategorySuccess', 'Category was deleted successfully');
        }

        return redirect()->back()->with('deleteCategoryFail', 'Category could not be deleted');
    }
}