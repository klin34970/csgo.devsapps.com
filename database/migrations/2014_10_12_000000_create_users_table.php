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
            $table->increments('id');
           // $table->uuid('id')->primary();
            $table->unsignedBigInteger('steamid')->unique();
            $table->string('personaname', 128);
            $table->string('profileurl', 128);
            $table->string('avatar', 128);
            $table->string('avatarmedium', 128);
            $table->string('avatarfull', 128);
            $table->enum('support', ['web', 'csgo']);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
