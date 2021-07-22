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
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('tax_id');
            $table->string('product',100);
            $table->string('barcode', 20)->unique();
            $table->tinyInteger('type');
            $table->tinyInteger('state');
            $table->decimal('cost_price', 10, 2);
            $table->decimal('gain', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->decimal('wholesale_price', 10, 2);
            $table->tinyInteger('stock')->default('0');
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('minimum', 10, 2)->nullable();
            $table->decimal('maximum', 10, 2)->nullable();

            $table->foreign('category_id')
            ->references('id')
            ->on('categories')
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
        Schema::dropIfExists('products');
    }
}
