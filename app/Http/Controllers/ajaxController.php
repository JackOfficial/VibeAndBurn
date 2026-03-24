<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\service;

class ajaxController extends Controller
{
    public function getServices($id)
    {

        $services = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->where('categories.id', '=', $id)
        ->where('services.status', '=', 1)
        ->select('services.*', 'categories.category')
         ->orderBy('services.service', 'ASC')
         ->get();

         $servicesCounter = service::join('categories', 'services.category_id', '=', 'categories.id')
         ->where('categories.id', '=', $id)
         ->where('services.status', '=', 1)
         ->count();
  
         $option="<option value=''>-- Select Service --</option>";

         if($servicesCounter !=0){
            
            foreach($services as $service){
              $price = $service->rate_per_1000;
              $option.="<option value='$service->id'><b>ID - " . $service->id ."</b> " . $service->service .' -- $'. $price ." per 1000</option>";
              $min_order = $service->min_order;
              $max_order = $service->max_order;
              $description = $service->description;
              
            }
          echo json_encode(["option"=>1, "value"=>$option, "status"=>1, "res"=>$price, "min_order"=>$min_order, "max_order" => $max_order, "description" => $description]);
         }
         else{
            echo json_encode(["option"=>0, "value"=>$option]); 
         }
   
    }

    public function getPrice($id)
    {

        $services = service::join('categories', 'services.category_id', '=', 'categories.id')
        ->where('services.id', '=', $id)
        ->where('services.status', '=', 1)
        ->select('services.rate_per_1000', 'services.min_order', 'services.max_order', 'services.description')
         ->get();

         $servicesCounter = service::join('categories', 'services.category_id', '=', 'categories.id')
         ->where('services.id', '=', $id)
         ->where('services.status', '=', 1)
         ->count();

         if($servicesCounter !=0){
            
            foreach($services as $service){
                $price = $service->rate_per_1000;
                $min_order = $service->min_order;
                $max_order = $service->max_order;
                $description = $service->description;
                echo json_encode(["status"=>1, "value"=>$price, "min_order"=>$min_order, "max_order" => $max_order, "description" => $description]); 
              }
            
         }
         else{   
            echo json_encode(["status"=>0, "value"=>0, "min_order"=>'', "max_order" => '']); 
         }

   
    }
}
