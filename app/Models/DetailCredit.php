<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_id',
        'barcode',
        'discount_percentage',
        'discount_price',
        'price_tax_exc',
        'price_tax_inc',
        'price_tax_inc_total',
        'quantity',
        'product',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
