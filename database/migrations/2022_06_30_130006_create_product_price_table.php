<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('warranty_id');
            $table->integer('product_id');
            $table->integer('color_id')->default(0);
            $table->integer('time');
            $table->string('Year',10);
            $table->string('mount',10);
            $table->string('day',10);
            $table->integer('price');
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
        Schema::dropIfExists('product_price');
    }
}
