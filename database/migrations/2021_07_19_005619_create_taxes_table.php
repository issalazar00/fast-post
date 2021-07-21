<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<< HEAD:database/migrations/2021_07_19_005609_create_taxes_table.php
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->float('percentage');
=======
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
>>>>>>> be1327880b737581f236b80d08d246093b0aa4a6:database/migrations/2021_06_29_044530_create_productos_table.php
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
        Schema::dropIfExists('taxes');
    }
}
