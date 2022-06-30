<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchCurrency;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchCurrencyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $branches = Branch::with('balances.currency')->get();
        $data['currencies'] = Currency::all();

        foreach ($branches as $key => $branch){
            $balances = [];

            foreach ($branch->balances as $balance) {
                $balances[$balance->currency->code]['balance'] = $balance->balance;
                $balances[$balance->currency->code]['is_limited'] = $balance->is_limited;
                $balances[$balance->currency->code]['updated_at'] = $balance->updated_at->format('Y-m-d H:i:s');
            }

            $data['branches'][$key]['name'] = $branch->name;
            $data['branches'][$key]['balances'] = $balances;
        }

        return view('branch-currency.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('branch-currency.create', ['branches' => Branch::all(), 'currencies' => Currency::all()]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'branch_id' => 'required|integer|exists:branches,id',
                'currency_id' => 'required|integer|exists:currencies,id',
                'balance' => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        $currency = Currency::find($data['currency_id']);

        if($currency->limit > $data['balance']) {
            $data = array_merge($data, ['is_limited' => true]);
        }

        BranchCurrency::create($data);

        return redirect()->route(
            'branch-currency.index', [
                                'success' => 'Филиал успешно создан.',
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
