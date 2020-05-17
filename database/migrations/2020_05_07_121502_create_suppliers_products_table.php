<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->integer('quantity');
            $table->bigInteger('parent_id')->default(0)->nullable();
            $table->bigInteger('parent_quantity')->default(0)->nullable();
            $table->double('sector_price')->default(0)->nullable();
            $table->double('buy_price')->default(0)->nullable();
            $table->double('wholesale')->default(0)->nullable();
            $table->double('wholesale_2')->default(0)->nullable();
            $table->string('par_code')->nullable();
            $table->string('par_code_2')->nullable();
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
        Schema::dropIfExists('suppliers_products');
    }
}