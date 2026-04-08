<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category;
use App\Models\service;
use App\Models\Source;

class serviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request)
{
    // Eager load relationships and sort by the service name
    $services = service::with(['category.socialmedia', 'source'])
        ->orderBy('service', 'asc')
        ->paginate(50);

    // Use total() to get the count of all records across all pages
    $servicesCounter = $services->total();

    return view('admin.services.index', compact('services', 'servicesCounter'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
public function create(Request $request)
{
    // Eager load social media and sort alphabetically by category name
    $categories = category::with('socialmedia')
        ->orderBy('category', 'asc')
        ->get();
    
    // Fetch all API sources
    $sources = Source::all();

    return view('admin.services.create', compact('categories', 'sources'));
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
{
    // 1. Precise Validation: Use numeric/integer types for math-heavy fields
    $validated = $request->validate([
        'category'                => 'required|exists:categories,id',
        'service'                 => 'required|string|max:255',
        'rate_per_1000'             => 'required|numeric|min:0',
        'min_order'               => 'required|integer|min:1',
        'max_order'               => 'required|integer|gt:min_order',
        'average_completion_time' => 'nullable|string|max:255',
        'serviceId'               => 'nullable|string|max:255',
        'source'                  => 'required|exists:sources,id',
        'quality'                 => 'nullable|string|max:255',
        'start'                   => 'nullable|string|max:255',
        'speed'                   => 'nullable|string|max:255',
        'refill'                  => 'nullable|string|max:255',
        'description'             => 'nullable|string',
    ]);

    // 2. Mass Assignment: Cleaner and more readable
    $service = service::create([
        'category_id'             => $validated['category'],
        'serviceId'               => $validated['serviceId'],
        'service'                 => $validated['service'],
        'rate_per_1000'           => $validated['rateper1000'],
        'min_order'               => $validated['min_order'],
        'max_order'               => $validated['max_order'],
        'Average_completion_time' => $validated['average_completion_time'],
        'quality'                 => $validated['quality'],
        'start'                   => $validated['start'],
        'speed'                   => $validated['speed'],
        'refill'                  => $validated['refill'],
        'source_id'               => $validated['source'],
        'description'             => $validated['description'],
        'status'                  => 1, // Default to Active
    ]);

    // 3. Conditional Redirect
    if ($service) {
        return redirect()->route('admin.service.index')
            ->with('addServiceSuccess', 'Service added successfully!');
    }

    return redirect()->back()
        ->with('addServiceFail', 'Failed to create service.')
        ->withInput(); // Keeps data in form so user doesn't re-type
}
    
public function toggler(Request $request, $id)
{
    // 1. Fetch the model once
    $service = service::findOrFail($id);

    // 2. Toggle the state directly on the model instance
    // This is more "Eloquent-native" than using a where()->update() query
    $service->state = ($service->state == 1) ? 0 : 1;
    $saved = $service->save();

    // 3. Return with dynamic messaging
    if ($saved) {
        $msg = $service->state == 1 ? 'Service enabled.' : 'Service disabled.';
        return redirect()->back()->with('deleteServiceSuccess', $msg);
    }

    return redirect()->back()->with('deleteServiceFail', 'The service status could not be changed.');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function show(Request $request, $id)
{
    // 1. Fetch the service (Standardized Capital 'S')
    $service = service::findOrFail($id);

    // 2. Toggle Logic: 1 to 0, 0 to 1
    // This replaces the entire if/else block with a single line
    $service->status = ($service->status == 1) ? 0 : 1;
    $saved = $service->save();

    // 3. Dynamic Messaging
    if ($saved) {
        $statusText = ($service->status == 1) ? 'activated' : 'disabled';
        
        return redirect()->back()->with(
            'deleteServiceSuccess', 
            "The service has been {$statusText} successfully."
        );
    }

    return redirect()->back()->with('deleteServiceFail', 'The service status could not be changed.');
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function edit(Request $request, $id)
{
    // 1. Fetch the service with its category pre-loaded
    $service = service::with('category')->findOrFail($id);
    
    // 2. Fetch categories with social media names (Eager Loading)
    // This replaces the join and ensures the dropdown has the social media names
    $categories = category::with('socialmedia')
        ->orderBy('category', 'ASC')
        ->get();
    
    // 3. Fetch all API sources
    $sources = Source::all();
    
    return view('admin.services.edit', compact('service', 'categories', 'sources'));
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
    // 1. Fetch the model first to ensure it exists
    $service = service::findOrFail($id);

    // 2. Strict Validation: Use numeric/integer types for data integrity
    $validated = $request->validate([
        'category'                => 'required|exists:categories,id',
        'service'                 => 'required|string|max:255',
        'rateper1000'             => 'required|numeric|min:0',
        'min_order'               => 'required|integer|min:1',
        'max_order'               => 'required|integer|gt:min_order',
        'average_completion_time' => 'nullable|string|max:255',
        'serviceId'               => 'nullable|string|max:255',
        'source'                  => 'required|exists:sources,id',
        'quality'                 => 'nullable|string|max:255',
        'start'                   => 'nullable|string|max:255',
        'speed'                   => 'nullable|string|max:255',
        'refill'                  => 'nullable|string|max:255',
        'description'             => 'nullable|string',
    ]);

    // 3. Update the Model Instance
    // Using update() on the model instance is cleaner than where()->update()
    $updated = $service->update([
        'category_id'             => $validated['category'],
        'service'                 => $validated['service'],
        'serviceId'               => $validated['serviceId'],
        'rate_per_1000'           => $validated['rateper1000'],
        'min_order'               => $validated['min_order'],
        'max_order'               => $validated['max_order'],
        'Average_completion_time' => $validated['average_completion_time'],
        'quality'                 => $validated['quality'],
        'start'                   => $validated['start'],
        'speed'                   => $validated['speed'],
        'refill'                  => $validated['refill'],
        'source_id'               => $validated['source'],
        'description'             => $validated['description'],
    ]);

    // 4. Return with feedback
    if ($updated) {
        return redirect()->back()->with('updateServiceSuccess', 'Service updated successfully!');
    }

    return redirect()->back()->with('updateServiceFail', 'The service could not be updated.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function destroy($id)
{
    // 1. Use the destroy method (Standardized Capital 'S')
    // This returns the number of rows deleted (1 if successful, 0 if not found)
    $deleted = service::destroy($id);

    // 2. Consistent Messaging
    if ($deleted) {
        return redirect()->back()->with('deleteServiceSuccess', 'The service has been deleted successfully.');
    }

    return redirect()->back()->with('deleteServiceFail', 'The service could not be deleted or was already removed.');
}
}
