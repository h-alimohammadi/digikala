<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOldPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_price', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('productWarranty_id');
            $table->integer('price1');
            $table->integer('price2');
            $table->integer('product_number');
            $table->integer('product_number_cart');
            $table->integer('number_product_sale');
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
        Schema::dropIfExists('old_price');
    }
}
