<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('iin')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });

        DB::table('accesses')->insert(
            [
                [
                    'code' => 'client',
                    'description' => 'Просмотр клиента',
                ],
                [
                    'code' => 'client_create',
                    'description' => 'Добавление клиента',
                ],
                [
                    'code' => 'client_edit',
                    'description' => 'Редактирования клиента',
                ],
            ]
        )
        ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
