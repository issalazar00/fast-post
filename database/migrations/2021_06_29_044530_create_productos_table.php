<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('codigo_barras')->unique();
            $table->string('producto');
            $table->tinyInteger('tipo_producto');
            $table->decimal('precio_costo', 20, 2);
            $table->decimal('valor_ganancia', 20, 2);
            $table->decimal('precio_venta', 20, 2);
            $table->decimal('precio_mayoreo', 20, 2);
            $table->tinyInteger('inventario')->default('0');
            $table->decimal('cantidad_actual', 10, 2)->nullable();
            $table->decimal('cantidad_minima', 10, 2)->nullable();
            $table->decimal('cantidad_maxima', 10, 2)->nullable();
            $table->tinyInteger('estado')->default('1');
            $table->foreignId('id_categoria');
            $table->foreignId('id_impuesto');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
