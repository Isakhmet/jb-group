<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Roles;
use App\Models\User;
use App\Models\UserBranch;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $users;

    public function __construct()
    {
        $this->users = User::with('roles')
                           ->get()
        ;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('users.index', ['users' => $this->users]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $roles = Roles::all();

        return view('users.create', ['roles' => $roles]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->toArray(),
            [
                'name' => 'required|string|unique:users,name',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        $user = User::create(
            [
                'name'     => $request->get('name'),
                'password' => Hash::make($request->get('password')),
            ]
        );

        UserRole::create(
            [
                'user_id' => $user->id,
                'role_id' => Roles::where('code', $request->get('role'))
                                  ->first(['id'])->id,
            ]
        );

        return redirect()->route(
            'users.index', [
                             'success' => 'Пользователь успешно создан.',
                             'users'   => $this->users,
                         ]
        );
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $user  = User::with('roles')
                     ->where('id', $id)
                     ->first()
        ;
        $roles = Roles::all();

        return view('users/edit', ['user' => $user, 'roles' => $roles]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (strcmp($user->name, $request->get('name')) !== 0) {
            $validator = Validator::make(
                $request->toArray(),
                [
                    'name' => 'required|string|unique:users,name',
                ]
            );

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ;
            }
        }

        $user->update(
            [
                'name'     => $request->get('name'),
                'password' => Hash::make($request->get('password')),
            ]
        );

        $roleId = Roles::where('code', $request->get('role'))->first()->id;

        UserRole::where('user_id', $user->id)
                ->updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'role_id' => $roleId,
                    ]
                )
        ;

        return redirect()->route(
            'users.index', [
                             'success' => 'Данные успешно обновлены.',
                             'users'   => $this->users,
                         ]
        );
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::with('roles')->find($id);

        UserRole::where('user_id', $id)->delete();
        UserBranch::where('user_id', $id)->delete();
        $user->delete();

        return redirect()
            ->back()
            ->with('success', 'Пользователь успешно удален')
            ;
    }

    public function addBranch()
    {
        $users = User::whereNotIn('name', ['admin'])->get();
        $branches = Branch::all();

        return view('users.add-branch', ['users' => $users, 'branches' => $branches]);
    }

    public function bindBranch(Request $request)
    {
        $rules = [
            'user_id' => 'unique:user_branches,user_id,NULL,id,branch_id,' . $request->get('branch_id'),
            'branch_id' => 'unique:user_branches,branch_id,NULL,id,user_id,' . $request->get('user_id')
        ];

        $validator = Validator::make($request->toArray(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        UserBranch::create($request->all());

        return redirect('/list-branch');
    }

    public function listBranch()
    {
        $users = UserBranch::with(['users', 'branches'])->get();

        return view('users.list-branch', ['users' => $users]);
    }

    public function destroyBranch($id)
    {
        UserBranch::find($id)->delete();

        return redirect()
            ->back()
            ->with('success', 'Запись успешно удалена')
            ;
    }
}
