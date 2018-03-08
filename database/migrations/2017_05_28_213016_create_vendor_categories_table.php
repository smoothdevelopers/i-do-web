<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('image_url');
            $table->tinyInteger('has_name');
            $table->tinyInteger('has_image');
            $table->tinyInteger('phone');
            $table->tinyInteger('email');
            $table->tinyInteger('location');
            $table->tinyInteger('slots');
            $table->tinyInteger('description');
            $table->tinyInteger('rating');
            $table->tinyInteger('website');
            $table->tinyInteger('cost');
            $table->tinyInteger('cost_terms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_categories');
    }
}
