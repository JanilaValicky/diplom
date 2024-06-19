<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PaymentWebController extends Controller
{
    public function index(Request $request, $plan_name)
    {
        $plan = Plan::where('name', $plan_name)->firstOrFail();

        return view('dashboard.pricing.payment.index', compact('plan'));
    }
}
