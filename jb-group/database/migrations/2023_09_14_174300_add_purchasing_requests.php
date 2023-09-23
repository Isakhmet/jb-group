<?php

use App\Models\Access;
use App\Models\RoleAccess;
use App\Models\Roles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchasingRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'purchasing_requests', function (Blueprint $table) {
            $table->bigInteger('status_id')
                  ->after('user_id')
            ;
            $table->foreign('status_id')
                  ->references('id')
                  ->on('statuses')
            ;
        }
        );


        $access = Access::create(
            [
                'code'        => 'directory',
                'description' => 'Справочники',
            ]
        );


        $data   = [];
        $roleId = Roles::where('code', 'admin')
                       ->first()->id
        ;

        $data['role_id']   = $roleId;
        $data['access_id'] = $access->id;;

        RoleAccess::create($data);
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
