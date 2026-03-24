<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\category;
use App\Models\service;
use App\Models\wallet;

class ourServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $YouTube = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('socialmedia.socialmedia', '=', 'Youtube')
       ->where('services.status', '=', 1)
        ->select('services.*', 'categories.category', 'socialmedia.socialmedia')
         ->orderBy('services.service', 'ASC')
         ->get();

         $YouTubeCounter = service::join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('socialmedia.socialmedia', '=', 'Youtube')
       ->where('services.status', '=', 1)
         ->count(); 
         
         $TikTok = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('socialmedia.socialmedia', '=', 'TikTok')
       ->where('services.status', '=', 1)
        ->select('services.*', 'categories.category', 'socialmedia.socialmedia')
         ->orderBy('services.service', 'ASC')
         ->get();

         $TikTokCounter = service::join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('socialmedia.socialmedia', '=', 'TikTok')
       ->where('services.status', '=', 1)
         ->count();
         
         $Instagram = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('socialmedia.socialmedia', '=', 'Instagram')
       ->where('services.status', '=', 1)
        ->select('services.*', 'categories.category', 'socialmedia.socialmedia')
         ->orderBy('services.service', 'ASC')
         ->get();

         $InstagramCounter = service::join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('socialmedia.socialmedia', '=', 'Instagram')
       ->where('services.status', '=', 1)
         ->count();
         
          $Facebook = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('socialmedia.socialmedia', '=', 'Facebook')
       ->where('services.status', '=', 1)
        ->select('services.*', 'categories.category', 'socialmedia.socialmedia')
         ->orderBy('services.service', 'ASC')
         ->get();

         $FacebookCounter = service::join('categories', 'services.category_id', '=', 'categories.id')
         ->join('socialmedia', 'categories.socialmedia_id', '=', 'socialmedia.id')
       ->where('socialmedia.socialmedia', '=', 'Facebook')
       ->where('services.status', '=', 1)
         ->count();

        return view('services', compact('YouTube', 'YouTubeCounter', 'TikTok', 'TikTokCounter', 'Instagram', 'InstagramCounter', 'Facebook', 'FacebookCounter'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
