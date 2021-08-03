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
        'phone',
        'mobile',
        'fax',
        'fisrtname_contact',
        'lastname_contact',
        'email',
        'giro_neg',
        'type_person',
        'departament',
        'city',
        'zipcode',
        'type_document',
        'document',
        'business_registration',
        'state',
        'tax'
    ];
}
