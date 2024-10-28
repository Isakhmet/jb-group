<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\Operation;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    public function index()
    {
        $currencies = CurrencyRate::with('currency')->get();

        //dd($currencies);
        return view('operations.index', [
            'currencies' => $currencies
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //dd($request->all());

        $rate = $request->get('type') === 'buy'
            ? $request->get('buy_rate')
            : $request->get('sell_rate');

        Operation::create([
            'type' => $request->get('type'),
            'currency_id' => $request->get('currency_id'),
            'amount' => $request->get('amount'),
            'rate' => $rate,
        ]);

        return redirect()->route('operations.index');
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

    public function history()
    {
        $profit = $this->calculateProfit();

        return view('operations.history', [
            'operations' => Operation::with('currency')->get(),
            'profit' => $profit
        ]);
    }

    /**
     * @return float|int
     */
    public function calculateProfit()
    {
        $operations = Operation::with('currency')->get();
        // Предполагается, что у вас есть связи с моделью курса (например, CurrentRate)
        $currentRates = CurrencyRate::all()->mapWithKeys(function ($rate) {
            return [
                $rate->currency_id => [
                    'buy_rate' => $rate->buy_rate,
                    'sell_rate' => $rate->sell_rate,
                ],
            ];
        });

        $profit = 0;
        $profits = [];

        foreach ($operations as $operation) {
            $currentRate = $currentRates[$operation->currency->id];
            if ($operation->type === 'buy') {
                // Для покупок: (Текущий курс продажи - Курс на момент покупки) * Количество
                $profits['buy'][$operation->currency->id][] = ($currentRate['sell_rate'] - $operation->rate) * $operation->amount;
                $profit += ($currentRate['sell_rate'] - $operation->rate) * $operation->amount;
            } elseif ($operation->type === 'sell') {
                // Для продаж: (Курс на момент продажи - Текущий курс покупки) * Количество
                $profits['sell'][$operation->currency->id][] = ($operation->rate - $currentRate['buy_rate']) * $operation->amount;
                $profit += ($currentRate['buy_rate'] - $operation->rate) * $operation->amount;
            }
        }

        return $profit;
    }
}
