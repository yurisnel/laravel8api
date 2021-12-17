<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();           
            $table->float('price');            
            
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');

            $table->unsignedBigInteger('attribute_options_id');
            $table->foreign('attribute_options_id')->references('id')->on('attribute_options')->onDelete('cascade');

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
        Schema::dropIfExists('product_attribute_values');
    }
}
