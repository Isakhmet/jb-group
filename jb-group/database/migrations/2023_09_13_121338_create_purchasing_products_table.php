<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchasing_requests_id');
            $table->integer('product_id');
            $table->integer('count');
            $table->timestamps();

            $table->foreign('purchasing_requests_id')->references('id')->on('purchasing_requests');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchasing_products');
    }
}
