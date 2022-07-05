<?php

namespace Database\Seeders;

use App\Models\Access;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleAccessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $roleId = Roles::where('code', 'admin')->first()->id;
        $accesses = Access::all();

        foreach ($accesses as $key => $access) {
            $data[$key]['role_id'] = $roleId;
            $data[$key]['access_id'] = $access->id;;
        }

        DB::table('role_accesses')->insert($data);
    }
}
