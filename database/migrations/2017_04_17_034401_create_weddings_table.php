<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeddingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weddings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('groom_id')->unsigned();
            $table->foreign('groom_id')->references('id')->on('users');
            $table->integer('bride_id')->unsigned();
            $table->foreign('bride_id')->references('id')->on('users');
            $table->string('img_url')->nullable();
            $table->string('description')->nullable();
            $table->string('venue')->nullable();
            $table->string('reception')->nullable();
            $table->tinyInteger('privacy')->default(config('const.privacy.private'));
            $table->timestamp('when')->nullable();
            $table->float('venue_lat')->nullable();
            $table->float('venue_lng')->nullable();
            $table->float('reception_lat')->nullable();
            $table->float('reception_lng')->nullable();
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
        Schema::dropIfExists('weddings');
    }
}
