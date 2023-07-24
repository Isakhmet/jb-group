<?php

use App\Models\Access;
use App\Models\Roles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePurchasingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('branch_id');
            $table->string('list');
            $table->timestamp('date');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');

        });

        $accessesCode = ['purchasing_view', 'purchasing_create', 'purchasing_update', 'purchasing_delete', 'purchasing_view_any'];
        $accessesDescription = ['Просмотр всех заявок', 'Создание заявок', 'Редактирование заявок', 'Удаления заявок', 'Просмотр заявок'];

        foreach ($accessesCode as $key => $access) {
            Access::create(
                [
                    'code' => $access,
                    'description' => $accessesDescription[$key],
                ]
            );
        }

        $data = [];
        $roleId = Roles::where('code', 'admin')->first()->id;
        $accesses = Access::whereIn('code', $accessesCode)->get();

        foreach ($accesses as $key => $access) {
            $data[$key]['role_id'] = $roleId;
            $data[$key]['access_id'] = $access->id;;
        }

        DB::table('role_accesses')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchasing_requests');
    }
}
