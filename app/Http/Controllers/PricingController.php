<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PricingController extends Controller
{

    /**
     * Displays product pricing list (ProductPricingComponent Vue component).
     *
     * @return Illuminate\Http\Response;
     */
    public function products ()
    {
        return view('prices.products');
    }

    /**
     * Displays service pricing list (ServicePricingComponent Vue component).
     *
     * @return Illuminate\Http\Response;
     */
    public function services ()
    {
        return view('prices.services');
    }

    /**
     * Displays product and service pricing lists (AllPricingComponent Vue component).
     *
     * @return Illuminate\Http\Response;
     */
    public function all ()
    {
        return view('prices.all');
    }
}
