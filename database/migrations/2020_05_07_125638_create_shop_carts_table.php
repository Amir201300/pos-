<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('productSupplier_id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->smallInteger('type')->nullable();
            $table->foreign('productSupplier_id')->references('id')->on('suppliers_products')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
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
        Schema::dropIfExists('shop_carts');
    }
}
