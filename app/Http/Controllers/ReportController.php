<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function reportSales()
    {
        $sales = Order::select(DB::raw('SUM(total_paid) as total_paid'), DB::raw('SUM(total_discount) as total_discount'), 'payment_date')
            ->selectRaw('count(id) as number_of_orders')
            ->selectRaw("count(case when state = '1' then 1 end) as suspended")
            ->selectRaw("count(case when state = '2' then 1 end) as registered")
            ->selectRaw("count(case when state = '3' then 1 end) as quoted")
            ->orderBy('payment_date', 'desc')
            ->groupBy('payment_date')->get();
        return $sales;
    }
}
