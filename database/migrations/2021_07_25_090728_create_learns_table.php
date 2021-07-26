<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learns', function (Blueprint $table) {
            $table->bigIncrements('learn_id');
            $table->string('learn_id_nk');
            $table->bigInteger('user_id');
            $table->string('title');
            $table->string('detail');
            $table->bigInteger('category_id');
            $table->bigInteger('lauguage_id');
            $table->string('learn_datetime');
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
        Schema::dropIfExists('learns');
    }
}
