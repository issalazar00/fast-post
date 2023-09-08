<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetTotalReportsController extends Controller
{

    public function getTotalReportPortfolio($results)
    {
        $data = [
            'interest_value' => $results->sum('interest_value'),
            'value' => $results->sum('value'),
            'capital_value' => $results->sum('capital_value')
        ];

        return $data;
    }

    public function getTotalReportExpenses($results){
        $data = [
            'price' => $results->sum('price')
        ];
        
        return $data;
    }

    public function getTotalReportHeadquartersEntries($results){
        $data = [
            'price' => $results->sum('price')
        ];
        
        return $data;
    }

    public function getTotalReportProductSales($results){
        $data = [
            'quantity_of_products' => $results->sum('quantity_of_products'),
            'price_tax_inc_of_products' => $results->sum('price_tax_inc_of_products'),
            'price_tax_exc_of_products' => $results->sum('price_tax_exc_of_products'),
            'cost_price_tax_inc_of_products' => $results->sum('cost_price_tax_inc_of_products')
        ];
        
        return $data;
    }
    
}
