<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;

class AddDateExpirationToConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $expirationDate= Carbon::now();
        Schema::table('configurations', function (Blueprint $table) use ($expirationDate) {
            $table->date('expiration_date')->nullable(false)->default($expirationDate->addMonths(2));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configurations', function (Blueprint $table) {
            //
        });
    }
}
