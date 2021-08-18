<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'barcode',
        'product',
        'type',
        'cost_price',
        'gain',
        'sale_price_tax_exc',
        'sale_price_tax_inc',
        'wholesale_price_tax_exc',
        'wholesale_price_tax_inc',
        'stock',
        'quantity',
        'minimum',
        'maximum',
        'state',
        'category_id',
        'tax_id'
    ];
    protected $with = [
        'category'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
