<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchCurrency;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BranchCurrencyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $branches           = Branch::with('balances.currency')
                                    ->get()
        ;
        $data['currencies'] = Currency::all();
        $data['branches'] = [];

        foreach ($branches as $key => $branch) {
            $balances = [];

            foreach ($branch->balances as $balance) {
                $balances[$balance->currency->code]['balance']    = $balance->balance;
                $balances[$balance->currency->code]['is_limited'] = $balance->is_limited;
                $balances[$balance->currency->code]['updated_at'] = $balance->updated_at->format('H:i:s');
            }

            $data['branches'][$key]['name']     = $branch->name;
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
        $request->merge(['balance' =>  (int)str_replace(',', '', $request->get('balance'))]);
        $data = $request->all();

        $rules = [
            'currency_id' => 'unique:branch_currencies,currency_id,NULL,id,branch_id,' . $request->get('branch_id'),
            'branch_id'   => 'unique:branch_currencies,branch_id,NULL,id,currency_id,' . $request->get('currency_id'),
            'balance'     => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        $currency = Currency::find($data['currency_id']);

        if ($currency->limit > $data['balance']) {
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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     *
     */
    public function edit()
    {
        $user = Auth::user();

        if(strcmp($user->roles->code, 'admin') === 0) {
            $branches = Branch::all();
        }else {
            $branches = $user->branches;
        }

        return view('branch-currency.edit', ['branches' => $branches]);
    }


    public function update(Request $request)
    {
        foreach ($request->get('currency') as $key => $currency) {
            $limit = Currency::find($key)->limit;
            $balance = (int)str_replace(',' , '', $currency);
            $isLimited = $balance >= $limit ? false : true;

            BranchCurrency::where('branch_id', $request->get('branch_id'))
                          ->where('currency_id', $key)
                          ->update(
                              [
                                  'balance' => $balance,
                                  'is_limited' => $isLimited
                              ]
                          );
        }

        return redirect()->back();
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        BranchCurrency::find($id)->delete();

        return redirect()->back();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getBalance(Request $request)
    {
        return BranchCurrency::with('currency')
                                ->where('branch_id', $request->get('id'))
                                ->get()
        ;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getBalanceByCurrency(Request $request)
    {
        return BranchCurrency::with(['currency', 'branch'])
                             ->where('currency_id', $request->get('id'))
                             ->orderBy('branch_id')
                             ->get();
    }
}
