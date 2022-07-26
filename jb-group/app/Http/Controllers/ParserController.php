<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchCurrency;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\RoleAccess;
use App\Models\User;
use App\Models\UserBranch;
use App\Models\UserRole;
use GuzzleHttp\Client;

class ParserController extends Controller
{
    public function accesses()
    {
        return RoleAccess::all();
    }

    public function branches()
    {
        return Branch::all();
    }

    public function currencies()
    {
        return Currency::all();
    }

    public function employees()
    {
        return Employee::all();
    }

    public function branchesCurrencies()
    {
        return BranchCurrency::all();
    }

    public function users()
    {
        return User::all();
    }

    public function userRoles()
    {
        return UserRole::all();
    }

    public function userBranches()
    {
        return UserBranch::all();
    }

    public function connectHost()
    {
        return new Client(['base_uri' => 'https://jb-group.kz/api/parser/']);
    }

    public function parseRoleAccesses()
    {
        $client = $this->connectHost();

        $results = $client->post('accesses');
        $array = json_decode($results->getBody()->getContents(), true);

        RoleAccess::truncate();
        RoleAccess::insert($array);
    }

    public function parseBranches()
    {
        $client = $this->connectHost();

        $results = $client->post('branches');
        $array = json_decode($results->getBody()->getContents(), true);

        Branch::insert($array);
    }

    public function parseCurrency()
    {
        $client = $this->connectHost();

        $results = $client->post('currencies');
        $array = json_decode($results->getBody()->getContents(), true);

        Currency::insert($array);
    }

    public function parseEmployee()
    {
        $client = $this->connectHost();

        $results = $client->post('employees');
        $array = json_decode($results->getBody()->getContents(), true);

        Employee::insert($array);
    }

    public function parseBranchCurrency()
    {
        $client = $this->connectHost();

        $results = $client->post('branches-currencies');
        $array = json_decode($results->getBody()->getContents(), true);

        BranchCurrency::insert($array);
    }

    public function parseUsers()
    {
        $client = $this->connectHost();

        $results = $client->post('users');
        $array = json_decode($results->getBody()->getContents(), true);
        $first = $array[0];
        User::where('id', $first['id'])->update(['name' => $first['name'], 'password' => $first['password']]);
        unset($array[0]);
        User::insert($array);
    }

    public function parseUsersRoles()
    {
        $client = $this->connectHost();

        $results = $client->post('user-roles');
        $array = json_decode($results->getBody()->getContents(), true);
        UserRole::truncate();
        UserRole::insert($array);
    }
}
