<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('address');
            $table->string('mobile')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('type_person')->nullable();
            $table->integer('departament')->nullable();
            $table->integer('city')->nullable();
            $table->tinyInteger('type_document');
            $table->integer('document');
            $table->tinyInteger('active')->default('1');
            $table->tinyInteger('tax')->default('1');
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
        Schema::dropIfExists('clients');
    }
}
