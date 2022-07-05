<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_currencies', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('branch_id');
            $table->smallInteger('currency_id');
            $table->integer('balance')->default(0);
            $table->boolean('is_limited')->default(false);
            $table->unique(['branch_id', 'currency_id']);
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_currencies');
    }
}
