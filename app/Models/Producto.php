<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_producto';

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo_barras',
        'producto',
        'tipo_producto',
        'precio_costo',
        'valor_ganancia',
        'precio_venta',
        'precio_mayoreo',
        'inventario',
        'cantidad_actual',
        'cantidad_minima',
        'cantidad_maxima',
        'estado',
        'id_categoria',
        'id_impuesto'
    ];
}
