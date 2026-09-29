<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InTouchPaymentService;

class Checkout extends Controller
{
   public $payment_method = 'momo'; // Options: 'momo' or 'cod'
}
