<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'code',
        'name',
        'address',       
        'mobile',      
        'contact',       
        'email',
        'type_person',
        'departament',
        'city',
        'type_document',
        'document',
        'active',
        'tax'
    ];
}
