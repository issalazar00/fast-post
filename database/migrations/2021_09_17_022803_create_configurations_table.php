<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('legal_representative', );
            $table->string('nit', 15);
            $table->string('address', 150);
            $table->string('email', 150);
            $table->string('tax_regime');
            $table->string('telephone', 15);
            $table->string('mobile', 15);
            $table->string('printer', 100);
            $table->text('logo')->nullable();
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
        Schema::dropIfExists('configurations');
    }
}
