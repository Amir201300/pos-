<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('address_ar')->nullable();
            $table->string('address_en')->nullable();
            $table->string('address_2_ar')->nullable();
            $table->string('address_2_en')->nullable();
            $table->string('phone_2')->nullable();
            $table->text('about_en')->nullable();
            $table->string('password')->nullable();
            $table->string('shipping_price')->nullable();
            $table->text('about_ar')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->smallInteger('status')->default(0)->nullable();
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
        Schema::dropIfExists('shops');
    }
}
