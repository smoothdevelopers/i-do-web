<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageBlacklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_blacklists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blocker_id')->unsigned();
            $table->foreign('blocker_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('blocked_id')->unsigned();
            $table->foreign('blocked_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('message_blacklists');
    }
}
