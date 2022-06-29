<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $currencies = Currency::all();

        return view('currencies.index', ['currencies' => $currencies]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('currencies.create');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code' => 'required|string|unique:currencies,code',
                'limit' => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        Currency::create($request->all());

        return redirect()->route(
            'currencies.index', [
                             'success' => 'Валюта успешно создана.',
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
        $currency = Currency::find($id);

        return view('currencies.edit', ['currency' => $currency]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $currency = Currency::find($id);

        if (strcmp($currency->code, $request->get('code')) !== 0) {
            $validator = Validator::make(
                $request->toArray(),
                [
                    'code' => 'required|string|unique:currencies,code',
                    'limit' => 'required|integer',
                ]
            );

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ;
            }
        }

        $currency->update($request->all());

        return redirect()->route('currencies.index', ['success' => 'Данные успешно обновлены.']);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Currency::find($id)
                ->delete()
        ;

        return redirect()
            ->back()
            ->with('success', 'удалено')
            ;
    }
}
