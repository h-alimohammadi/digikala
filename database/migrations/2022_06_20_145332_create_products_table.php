<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('ename')->nullable();
            $table->string('product_url');
            $table->integer('price')->nullable();
            $table->integer('discount_price')->nullable();
            $table->smallInteger('show')->default(1);
            $table->integer('view')->default(0);
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->smallInteger('special')->default(0);
            $table->integer('cat_id');
            $table->integer('brand_id');
            $table->string('image_url')->nullable();
            $table->text('tozihat')->nullable();
            $table->integer('order_number')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
