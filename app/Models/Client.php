<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';

    protected $fillable = [
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
