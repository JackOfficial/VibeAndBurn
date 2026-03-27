<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BFCurrencyController extends Controller
{
    public function index(){
        return view('admin.bfcurrency.index');
    }
}
