<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('tax_id');
            $table->string('name',100);
            $table->bigInteger('barcode')->unique();
            $table->integer('type');
            $table->double('cost_price');
            $table->double('gain');
            $table->double('sale_price');
            $table->double('wholesale_price');
            $table->integer('amount');
            $table->integer('minimum');
            $table->integer('maximum');

            $table->foreign('department_id')
            ->references('id')
            ->on('departments')
            ->onDelete('cascade');

            $table->foreign('tax_id')
            ->references('id')
            ->on('taxes')
            ->onDelete('cascade');

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
