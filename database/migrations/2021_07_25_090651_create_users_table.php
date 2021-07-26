<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('user_id_nk');
            $table->string('user_name');
            $table->string('mail_address');
            $table->string('address');
            $table->string('tel_number');
            $table->date('birthday');
            $table->string('password');
            $table->string('job');
            $table->string('profile_photo');
            $table->string('profile');
            $table->string('admin_f');
            $table->datetime('create_at');
            $table->datetime('update_at');
            $table->datetime('delete_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
