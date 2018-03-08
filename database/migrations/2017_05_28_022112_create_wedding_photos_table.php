<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeddingPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wedding_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('img_url');
            $table->integer('wedding_id')->unsigned();
            $table->foreign('wedding_id')->references('id')->on('weddings')->onDelete('cascade');
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
        Schema::dropIfExists('wedding_photos');
    }
}
