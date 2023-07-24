<?php

namespace App\Http\Controllers;

use App\Models\PurchasingRequests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchasingRequestsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('purchasing.index', ['purchasings' => PurchasingRequests::with('branches')->get()]);
    }


    public function create()
    {
        return view('purchasing.create', ['branches' => auth()->user()->branches]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|integer',
            'list_date' => 'required|date',
            'list' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        $date = Carbon::createFromTimestamp(strtotime($request->get('list_date')));

        PurchasingRequests::create(
            [
                'branch_id' => $request->get('branch_id'),
                'date' => $date,
                'list' => $request->get('list'),
            ]
        );

        return redirect()->route(
            'purchasing.index', [
                                  'success' => 'Заявка успешно создана.',
                              ]
        );
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


    public function edit($id)
    {
        $data = [
            'purchasing' => PurchasingRequests::find($id),
            'branches' => auth()->user()->branches
        ];
        return view('purchasing.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|integer',
            'list_date' => 'required|date',
            'list' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        $purchasing = PurchasingRequests::find($id);
        $purchasing->branch_id = $request->get('branch_id');
        $purchasing->date = Carbon::createFromTimestamp(strtotime($request->get('list_date')));
        $purchasing->list = $request->get('list');
        $purchasing->save();

        return redirect()->route(
            'purchasing.index', [
                                  'success' => 'Заявка успешно создана.',
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
        PurchasingRequests::find($id)->delete();

        return redirect()->route(
            'purchasing.index', [
                               'success' => 'Данные обновлены.',
                           ]
        );
    }
}
