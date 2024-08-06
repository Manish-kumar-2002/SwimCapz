<?php

namespace App\Http\Controllers;

use App\Traits\ShippingTrait;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    use ShippingTrait;

    public function testCurrencyExchange()
    {
        $this->setCurrencyExchangeRate();
        
    }
}

