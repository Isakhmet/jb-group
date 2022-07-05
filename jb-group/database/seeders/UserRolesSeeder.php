<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert(
              [
                  [
                      'role_id' => Roles::where('code', 'admin')->first()->id,
                      'user_id' => User::where('name', 'admin')->first()->id,
                  ]
              ]
          )
        ;
    }
}
