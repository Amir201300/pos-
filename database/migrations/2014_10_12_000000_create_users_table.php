<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('frist_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->smallInteger('status')->default(0)->nullable();
            $table->integer('code')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->smallInteger('social')->default(0)->nullable();
            $table->boolean('is_active')->default(0)->nullable();
            $table->string('lang',10)->nullable();
            $table->smallInteger('notification')->default(0)->nullable();
            $table->smallInteger('message')->default(0)->nullable();
            $table->double('catr_price')->default(0)->nullable();
            $table->bigInteger('discount_id')->default(0)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
