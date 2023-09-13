<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\PurchasingProduct;
use App\Models\PurchasingRequests;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchasingRequestsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if(strcmp($user->roles->code, 'admin') === 0) {
            $purchasing = PurchasingRequests::where('status_id', Status::where('name', 'new')->first()?->id)
                                            ->get();
        }else {
            $purchasing = PurchasingRequests::where('user_id', $user->id)->get();
        }

        return view('purchasing.index', ['purchasingRequests' => $purchasing]);
    }


    public function create()
    {
        return view('purchasing.create', [
            'userName' => auth()->user()->name,
            'branches' => auth()->user()->branches,
            'productTypes' => ProductType::with('products')->get()
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'list_date' => 'required|date',
            'items' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ;
        }

        $purchasingRequest = new PurchasingRequests();
        $purchasingRequest->branch_id = $request->get('branch_id');
        $purchasingRequest->date = Carbon::createFromTimestamp(strtotime($request->get('list_date')));
        $purchasingRequest->list = $request->get('list');
        $purchasingRequest->user_id = Auth::user()->id;
        $purchasingRequest->status_id = Status::where('name', 'new')->first()?->id;
        $purchasingRequest->save();

        foreach ($request->get('items') as $key => $item) {
            if((int)$item === 0) {
                continue;
            }

            PurchasingProduct::create(
                [
                    'purchasing_requests_id' => $purchasingRequest->id,
                    'product_id' => $key,
                    'count' => $item,
                ]
            );
        }

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
