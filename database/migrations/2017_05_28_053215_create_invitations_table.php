<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('message')->nullable();
            $table->integer('secured_slots')->default(0);
            $table->integer('slots')->unsigned()->defualt(1);
            $table->integer('theme')->unsigned()->nullable();
            $table->foreign('inviter_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('invitee_id')->unsigned();
            $table->foreign('invitee_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('wedding_id')->unsigned();
            $table->foreign('wedding_id')->references('id')->on('weddings')->onDelete('cascade');
            $table->integer('inviter_id')->unsigned();
            $table->boolean('accepted')->default(false);
            $table->tinyInteger('type')->default(config('const.invitation.app'));
            $table->timeStamps();
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
        Schema::dropIfExists('invitations');
    }
}
