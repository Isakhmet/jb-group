<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductDirectory extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::all();

        return view('directory.products.index', ['products' => $products]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('directory.products.create', ['types' => ProductType::all()->pluck('name', 'id')]);
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
                'name'        => 'required|string',
                'description' => 'required|string',
                'product_type_id' => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        Product::create($request->all());

        return redirect()->route(
            'product-directory.index', [
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
        $productTypes = ProductType::all()->pluck('name', 'id');

        return view('directory.products.edit', ['productTypes' => $productTypes, 'product' => Product::find($id)]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (strcmp($product->name, $request->get('name')) !== 0) {
            $validator = Validator::make(
                $request->toArray(),
                [
                    'name'        => 'required|string|unique:branches,name',
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

        $product->update($request->all());

        return redirect()->route('product-directory.index', ['success' => 'Данные успешно обновлены.']);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Product::find($id)->delete();

        return redirect()->route('product-directory.index', ['success' => 'Данные успешно обновлены.']);
    }
}
