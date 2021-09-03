<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id');
      $table->foreignId('client_id');
      $table->string('no_invoice')->rand(1, 10);
      $table->decimal('total_paid');
      $table->decimal('total_iva_inc');
      $table->decimal('total_iva_exc');
      $table->decimal('total_discount');
      $table->tinyInteger('state')->default('1');

      $table->foreign('user_id')
        ->references('id')
        ->on('users');

      $table->foreign('client_id')
        ->references('id')
        ->on('clients');

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
    Schema::dropIfExists('orders');
  }
}
