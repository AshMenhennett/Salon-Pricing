<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function products ()
    {
        return view('prices.products');
    }

    public function services ()
    {
        return view('prices.services');
    }

    public function all ()
    {
        return view('prices.all');
    }
}
