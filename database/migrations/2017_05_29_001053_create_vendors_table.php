<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('image_url')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->float('lat')->nullable();
            $table->float('lng')->nullable();
            $table->integer('slots')->nullable();
            $table->text('description')->nullable();
            $table->float('rating')->nullable();
            $table->string('website')->nullable();
            $table->string('cost_terms')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
