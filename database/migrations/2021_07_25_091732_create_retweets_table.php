<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retweets', function (Blueprint $table) {
            $table->bigIncrements('retweet_id');
            $table->string('retweet_id_nk');
            $table->bigInteger('user_id');
            $table->bigInteger('learn_id');
            $table->datetime('retweet_datetime');
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
        Schema::dropIfExists('retweets');
    }
}
