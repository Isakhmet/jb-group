<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\RoleAccess;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccessController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $roleAccesses = RoleAccess::all();
//dd($roleAccesses[0]->accesses);
        return view('accesses.index', ['roleAccesses' => $roleAccesses]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $roles = Roles::all();
        $accesses = Access::all();

        return view('accesses.create', ['roles' => $roles, 'accesses' => $accesses]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'access_id' => 'unique:role_accesses,access_id,NULL,id,role_id,' . $request->get('role_id'),
            'role_id'   => 'unique:role_accesses,role_id,NULL,id,access_id,' . $request->get('access_id'),
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        RoleAccess::create($request->all());

        return redirect()->route('accesses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $roles = Roles::all();
        $accesses = Access::all();

        $roleAccesses = RoleAccess::find($id);

        return view('accesses.edit', ['roles' => $roles, 'accesses' => $accesses, 'roleAccesses' => $roleAccesses]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'access_id' => 'unique:role_accesses,access_id,NULL,id,role_id,' . $request->get('role_id'),
            'role_id'   => 'unique:role_accesses,role_id,NULL,id,access_id,' . $request->get('access_id'),
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        RoleAccess::where('id', $id)->update($request->all());

        return redirect()->route('accesses.index');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        RoleAccess::find($id)->delete();

        return redirect()->route('accesses.index');
    }
}
