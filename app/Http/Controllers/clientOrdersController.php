<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use App\Models\User;
use App\Models\wallet;
use App\Models\fund;
use App\Models\BFCurency;
use Illuminate\Support\Facades\DB;

class clientOrdersController extends Controller
{
     public function index()
    {
        return view('admin.orders.index');
    }
}
