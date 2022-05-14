<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
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

    protected $with = [
        'client',
    ];

    protected $appends = [
        'paid_payment'
    ];

    public function detailCredits()
    {
        return $this->hasMany(DetailCredit::class);
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
