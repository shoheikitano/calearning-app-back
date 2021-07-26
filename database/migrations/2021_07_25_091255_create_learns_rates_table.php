<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearnsRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learns_rates', function (Blueprint $table) {
            $table->bigIncrements('learns_rate_id');
            $table->string('learns_rate_id_nk');
            $table->bigInteger('user_id');
            $table->bigInteger('learns_amount');
            $table->datetime('created_at');
            $table->datetime('updated_at');
            $table->datetime('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learns_rates');
    }
}
