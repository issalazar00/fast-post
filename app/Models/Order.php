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
        'total_discount',
        'state'
    ];

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}
