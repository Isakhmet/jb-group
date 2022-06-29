<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(
              [
                  [
                      'code' => 'admin',
                      'name' => 'Администратор',
                  ],
                  [
                      'code' => 'director',
                      'name' => 'Директор организации',
                  ],
                  [
                      'code' => 'employee',
                      'name' => 'Кассир',
                  ],
              ]
          )
        ;
    }
}
