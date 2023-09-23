<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductTypeDirectory extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $productTypes = ProductType::all();

        return view('directory.product-types.index', ['productTypes' => $productTypes]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('directory.product-types.create');
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
                'name'        => 'required|string|unique:product_types,name',
                'description' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        ProductType::create($request->all());

        return redirect()->route(
            'product-type-directory.index', [
                                              'success' => 'успешно создан.',
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
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $productType = ProductType::find($id);

        return view('directory.product-types.edit', ['productType' => $productType]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $productType = ProductType::find($id);

        if (strcmp($productType->name, $request->get('name')) !== 0) {
            $validator = Validator::make(
                $request->toArray(),
                [
                    'name'        => 'required|string|unique:product_types,name',
                    'description' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ;
            }
        }

        $productType->update($request->all());

        return redirect()->route('product-type-directory.index', ['success' => 'Данные успешно обновлены.']);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        ProductType::find($id)->delete();

        return redirect()->route('product-type-directory.index', ['success' => 'Данные успешно обновлены.']);
    }
}
