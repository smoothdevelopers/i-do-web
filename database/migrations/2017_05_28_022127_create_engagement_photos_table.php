<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngagementPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engagement_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('img_url');
            $table->integer('engagement_id')->unsigned();
            $table->foreign('engagement_id')->references('id')->on('engagements')->onDelete('cascade');
            $table->tinyInteger('privacy')->default(config('const.privacy.private'));
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
        Schema::dropIfExists('engagement_photos');
    }
}
