<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('fisrtname_contact')->nullable();
            $table->string('lastname_contact')->nullable();
            $table->string('email')->nullable();
            $table->string('giro_neg')->nullable();
            $table->string('type_person')->nullable();
            $table->integer('departament')->nullable();
            $table->integer('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->tinyInteger('type_document');
            $table->integer('document');
            $table->string('business_registration')->nullable();
            $table->tinyInteger('state')->default('1');
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
        Schema::dropIfExists('suppliers');
    }
}
