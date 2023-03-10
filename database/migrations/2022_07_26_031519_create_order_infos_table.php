<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->softDeletes();
            $table->integer('order_id');
            $table->string('delivery_order_interval');
            $table->string('send_order_amount');
            $table->string('product_id');
            $table->string('warranty_id')->nullable();
            $table->string('colors_id')->nullable();
            $table->string('send_status');
            $table->integer('order_send_time');
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
        Schema::dropIfExists('order_infos');
    }
}
