<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accesses')->insert(
              [
                  [
                      'code' => 'user_view',
                      'description' => 'Просмотр пользователя',
                  ],
                  [
                      'code' => 'user_add',
                      'description' => 'Добавление пользователя',
                  ],
                  [
                      'code' => 'user_edit',
                      'description' => 'Редактирования пользователя',
                  ],
                  [
                      'code' => 'user_branch_access_view',
                      'description' => 'Список доступов пользователей к филиалом',
                  ],
                  [
                      'code' => 'user_branch_access_add',
                      'description' => 'Дать доступ пользователям к филиалу',
                  ],
                  [
                      'code' => 'currency',
                      'description' => 'Валюты',
                  ],
                  [
                      'code' => 'currency_add',
                      'description' => 'Добавление валют',
                  ],
                  [
                      'code' => 'currency_edit',
                      'description' => 'Редактирования валют',
                  ],
                  [
                      'code' => 'branch_view',
                      'description' => 'Просмотр филиалов',
                  ],
                  [
                      'code' => 'branch_add',
                      'description' => 'Добавление филиалов',
                  ],
                  [
                      'code' => 'branch_edit',
                      'description' => 'Редактирования филиалов',
                  ],
                  [
                      'code' => 'access_config',
                      'description' => 'Доступы',
                  ],
                  [
                      'code' => 'balance_view',
                      'description' => 'Смотреть остаток валют',
                  ],
                  [
                      'code' => 'balance_edit',
                      'description' => 'Редактирования остатков валют',
                  ],
                  [
                      'code' => 'balance_add',
                      'description' => 'Добавление валюты в филиал',
                  ],
                  [
                      'code' => 'employee',
                      'description' => 'Смотреть кассиров',
                  ],
                  [
                      'code' => 'employee_create',
                      'description' => 'Добавление кассиров',
                  ],
                  [
                      'code' => 'employee_edit',
                      'description' => 'Редактирования кассиров',
                  ],
              ]
          )
        ;
    }
}
