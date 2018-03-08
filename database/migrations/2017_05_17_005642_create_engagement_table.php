<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engagements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('groom_id')->unsigned()->nullable();
            $table->foreign('groom_id')->references('id')->on('users');
            $table->integer('bride_id')->unsigned()->nullable();
            $table->foreign('bride_id')->references('id')->on('users');
            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->date('proposal_date')->nullable();
            $table->tinyInteger('culture')->nullable();
            $table->double('proposal_lat')->nullable();
            $table->double('proposal_lng')->nullable();
            $table->text('proposal_place')->nullable();
            $table->string('img_url')->nullable();
            $table->text('phrase')->nullable();
            $table->text('suprise_other')->nullable();
            $table->text('proposal_plan')->nullable();
            $table->boolean('is_surprise')->nullable();
            $table->tinyInteger('status')->default(config('const.engagement.status.pending'));
            $table->tinyInteger('privacy')->default(config('const.privacy.private'));
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
        Schema::dropIfExists('engagements');
    }
}
