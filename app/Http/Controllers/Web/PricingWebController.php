<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Support\Facades\View;

class PricingWebController extends Controller
{
    public function index()
    {
        $plans = Plan::all();

        return View::make('dashboard.pricing.index', compact('plans'));
    }
}
