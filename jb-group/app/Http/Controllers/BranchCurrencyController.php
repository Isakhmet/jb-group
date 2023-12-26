<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchCurrency;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BranchCurrencyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $branches = Branch::whereHas('balances.currency', function ($query) use ($request) {
            $query->where('is_additional', $request->has('is_additional'));
        })->get();

        $data['currencies'] = Currency::where('is_additional', $request->has('is_additional'))->get();
        $data['branches']   = [];
        $sum                = [];

        foreach ($data['currencies'] as $currency) {
            $sum[$currency->code] = 0;
        }

        foreach ($branches as $key => $branch) {
            $balances = [];

            foreach ($branch->branchCurrencies($request->has('is_additional')) as $balance) {
                $sum[$balance->currency->code] += $balance->balance;

                $balances[$balance->currency->code]['balance']    = $balance->balance;
                $balances[$balance->currency->code]['change']     = $balance->change;
                $balances[$balance->currency->code]['is_limited'] = $balance->is_limited;
                $balances[$balance->currency->code]['updated_at'] = $balance->updated_at->format('H:i:s');
            }

            $data['branches'][$key]['name']     = $branch->name;
            $data['branches'][$key]['balances'] = $balances;
        }

        $data['currenciesSum'] = $sum;

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
        $request->merge(['balance' => (int)str_replace(',', '', $request->get('balance'))]);
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

        return redirect()->route('branch-currency.index', ['success' => 'Филиал успешно создан.']);
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

        if (strcmp($user->roles->code, 'admin') === 0) {
            $branches = Branch::all();
        } else {
            $branches = $user->branches;
        }

        return view('branch-currency.edit', ['branches' => $branches]);
    }


    public function update(Request $request)
    {
        foreach ($request->get('currency') as $key => $currency) {
            $balance = (int)str_replace(',', '', $currency);
            $model   = BranchCurrency::where('branch_id', $request->get('branch_id'))
                                     ->where('currency_id', $key)
                                     ->first()
            ;

            if (($balance - $model->balance) > 0) {
                $model->change = true;
            } elseif (($balance - $model->balance)) {
                $model->change = false;
            }

            $limit     = Currency::find($key)->limit;
            $isLimited = $balance >= $limit ? false : true;

            $model->balance    = $balance;
            $model->is_limited = $isLimited;
            $model->updated_at = Carbon::now();
            $model->update();
        }

        return redirect()->back();
    }

    public function delete()
    {
        $branches = BranchCurrency::with('branch', 'currency')->get();

        return view('branch-currency.delete', ['branches' => $branches]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        BranchCurrency::find($id)
                      ->delete()
        ;

        return redirect()->back();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getBalance(Request $request)
    {
        $branchCurrencies = Branch::query()->with(['currencies', 'balances'])->where('id', $request->get('id'))->first();
        $currencies = $branchCurrencies->currencies;
        $balances = $branchCurrencies->balances;
        $data = [];

        foreach ($currencies as $key => $currency) {
            $balance = $balances->where('currency_id', $currency->id)->first();
            $data[$key]['code'] = $currency->code;
            $data[$key]['balance'] = $balance->balance;
            $data[$key]['is_limited'] = $balance->is_limited;
            $data[$key]['currency_id'] = $balance->currency_id;
        }

        return $data;
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
                             ->get()
            ;
    }
}
