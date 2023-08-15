<?php

use App\Models\Access;
use App\Models\Roles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMediaFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('format');
            $table->smallInteger('album_id')->nullable();
            $table->timestamps();

            $table->foreign('album_id')->on('media_albums')->references('id');
        });

        $accessesCode = ['media', 'media_create', 'media_edit', 'media_delete'];
        $accessesDescription = ['Просмотр медиафайлов', 'Добавление медиафайлов', 'Редактирование медиафайлов', 'Удаления медиафайлов'];

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
        Schema::dropIfExists('media_files');
    }
}
