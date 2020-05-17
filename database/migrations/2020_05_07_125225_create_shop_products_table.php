<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('productSupplier_id');
            $table->unsignedBigInteger('shop_id');
            $table->foreign('productSupplier_id')->references('id')->on('suppliers_products')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('name_ar')->nullable();
            $table->string('par_code')->nullable();
            $table->string('par_code_2')->nullable();
            $table->string('name_en')->nullable();
            $table->double('sector_price')->default(0)->nullable();
            $table->double('buy_price')->default(0)->nullable();
            $table->double('wholesale')->default(0)->nullable();
            $table->double('wholesale_2')->default(0)->nullable();
            $table->boolean('is_offer')->default(0)->nullable();
            $table->integer('offer_amount')->default(0)->nullable();
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
        Schema::dropIfExists('shop_products');
    }
}
