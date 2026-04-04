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
    $services = Service::with(['category.socialmedia', 'source'])
        ->orderBy('service', 'asc')
        ->paginate(50);

    // Use total() to get the count of all records across all pages
    $servicesCounter = $services->total();

    return view('admin.manage.services', compact('services', 'servicesCounter'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       $categories = category::join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->select('categories.*', 'socialmedia.socialmedia')
         ->orderBy('categories.category', 'ASC')
         ->get();
         
          $sources = Source::all();

        return view('admin.add.service', compact('categories', 'sources'));
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
            'category' => ['required', 'string', 'max:255'],
            'service' => ['required', 'string', 'max:255'],
            'rateper1000' => ['required', 'string', 'max:255'],
            'min_order' => ['required', 'string', 'max:255'],
            'max_order' => ['required', 'string', 'max:255'],
            'average_completion_time' => ['required', 'string', 'max:255'],
            'serviceId' => ['string', 'max:255'],
            'source' => ['required']
            ]);

           $service = service::create([
                'category_id'=>$request->input('category'),
                 'serviceId' => $request->input('serviceId'),
                'service'=>$request->input('service'),
                'rate_per_1000'=>$request->input('rateper1000'),
                'min_order'=>$request->input('min_order'),
                'max_order'=>$request->input('max_order'),
                'Average_completion_time'=>$request->input('average_completion_time'),
                'quality'=>$request->input('quality'),
                'start'=>$request->input('start'),
                'speed'=>$request->input('speed'),
                'refill'=>$request->input('refill'),
                'source_id'=>$request->input('source'),
                'description'=>$request->input('description'),
                ]);

                if($service){
                return redirect()->back()->with('addServiceSuccess','Service has been added successfully');
                }
                else{
                    return redirect()->back()->with('addServiceFail','Service could not be added');
                }

    }
    
    public function toggler(Request $request, $id){
        if($request->session()->has('adminName')){
            
        $name = $request->Session()->get('adminName');
             
        $status = service::findOrFail($id);
        
        $service = service::where('id', $id)->update([
            'state' => ($status->state == 1) ? 0 : 1
            ]);
            
            if($service){
              return redirect()->back()->with('deleteServiceSuccess', 'service updated');
            }
            else{
                return redirect()->back()->with('deleteServiceFail', 'the service could not be updated');
            } 
        
    }
         else{
            return view('auth.admin-login'); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
       $status = service::findOrFail($id);
        
        if($status->status == 1){
           $services = service::where('id', $id)->update([
            'status' => 0
            ]);
            
            if($services){
               return redirect()->back()->with('deleteServiceSuccess', 'the service has been disabled');
            }
            else{
                return redirect()->back()->with('deleteServiceFail', 'the service could not be disabled');
            } 
        }
        else{
            $services = service::where('id', $id)->update([
            'status' => 1
            ]);
            
            if($services){
               return redirect()->back()->with('deleteServiceSuccess', 'the service has been activated');
            }
            else{
                return redirect()->back()->with('deleteServiceFail', 'the service could not be activated');
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $service = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->where('services.id', '=', $id)
        ->select('services.*', 'categories.category')
         ->first();
         
         $categories = category::join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
        ->select('categories.*', 'socialmedia.socialmedia')
         ->orderBy('categories.category', 'ASC')
         ->get();
         
         $sources = Source::all();
         
        return view('admin.edit.service', compact('service', 'categories',  'sources'));
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
            'category' => ['required', 'string', 'max:255'],
            'service' => ['required', 'string', 'max:255'],
            'rateper1000' => ['required', 'string', 'max:255'],
            'min_order' => ['required', 'string', 'max:255'],
            'max_order' => ['required', 'string', 'max:255'],
            'source' => ['required']
            ]);

           $service = service::where('id', '=', $id)->update([
                'category_id'=>$request->input('category'),
                'service'=>$request->input('service'),
                'serviceId' => $request->input('serviceId'),
                'rate_per_1000'=>$request->input('rateper1000'),
                'min_order'=>$request->input('min_order'),
                'max_order'=>$request->input('max_order'),
                'Average_completion_time'=>$request->input('average_completion_time'),
                'quality'=>$request->input('quality'),
                'start'=>$request->input('start'),
                'speed'=>$request->input('speed'),
                'refill'=>$request->input('refill'),
                 'source_id'=>$request->input('source'),
                'description'=>$request->input('description'),
                ]);

                if($service){
                return redirect()->back()->with('updateServiceSuccess','Service has been updated successfully');
                }
                else{
                    return redirect()->back()->with('updateServiceFail','Service could not be updated');
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
        $service = service::where('id',$id)->delete();

        if($service){
            return redirect()->back()->with('deleteServiceSuccess', 'Social media was deleted successfully');
        }
        else{
            return redirect()->back()->with('deleteServiceFail', 'Social media could not be deleted');
        }
    }
}
