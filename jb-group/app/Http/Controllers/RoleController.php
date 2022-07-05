<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private Roles $repository;
    private Request $request;

    public function __construct(Request $request, Roles $roles)
    {
        $this->repository = $roles;
        $this->request    = $request;
    }

    /**
     * @return \App\Models\Roles[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->repository::all();
    }


    public function create()
    {
        //
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'code' => 'required|string',
                'name' => 'required|string',
            ]
        );

        $this->repository->create(
            [
                'name' => $request->get('name'),
                'code' => $request->get('code'),
            ]
        );

        return redirect('roles')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
