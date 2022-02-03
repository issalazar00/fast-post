<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'no_invoice',
        'total_paid',
        'total_iva_inc',
        'total_iva_exc',
        'total_cost_price_tax_inc',
        'total_discount',
        'payment_date',
        'state'
    ];

    protected $with = [
        'client'
    ];

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
