<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\PurchasingProduct;
use App\Models\PurchasingRequests;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchasingRequestsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if (strcmp($user->roles->code, 'admin') === 0) {
            $purchasing = PurchasingRequests::where('status_id', Status::where('name', 'new')->first()?->id)
                ->get();
        } else {
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
                ->withErrors($validator);
        }

        $purchasingRequest = new PurchasingRequests();
        $purchasingRequest->branch_id = $request->get('branch_id');
        $purchasingRequest->date = Carbon::createFromTimestamp(strtotime($request->get('list_date')));
        $purchasingRequest->list = $request->get('list');
        $purchasingRequest->user_id = Auth::user()->id;
        $purchasingRequest->status_id = Status::where('name', 'new')->first()?->id;
        $purchasingRequest->save();

        foreach ($request->get('items') as $key => $item) {
            if ((int)$item === 0) {
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
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $purchasingModel = PurchasingRequests::find($id);
        $productTypes = [];

        foreach ($purchasingModel->purchasingProducts as $key => $purchasingProduct) {
            if ($purchasingProduct->product->type->name) {
                $productTypes[$purchasingProduct->product->type->name][$key]['product'] = $purchasingProduct->product;
                $productTypes[$purchasingProduct->product->type->name][$key]['count'] = $purchasingProduct->count;
            }
        }

        $data = [
            'productTypes' => $productTypes,
            'purchasing' => $purchasingModel,
            'branches' => auth()->user()->branches,
            'statuses' => Status::all()->pluck('description', 'id'),
            'onlyList' => false
        ];

        return view('purchasing.show', $data);
    }


    public function edit($id)
    {
        $purchasingModel = PurchasingRequests::find($id);
        $productTypes = [];

        foreach ($purchasingModel->purchasingProducts as $key => $purchasingProduct) {
            if ($purchasingProduct->product->type->name) {
                $productTypes[$purchasingProduct->product->type->name][$key]['product'] = $purchasingProduct->product;
                $productTypes[$purchasingProduct->product->type->name][$key]['count'] = $purchasingProduct->count;
            }
        }

        $data = [
            'productTypes' => $productTypes,
            'purchasing' => $purchasingModel,
            'branches' => auth()->user()->branches
        ];

        return view('purchasing.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'branch' => 'required|string',
            'list_date' => 'date',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $purchasing = PurchasingRequests::find($id);
        $purchasing->status_id = $request->get('status_id');
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

    public function allList()
    {
        $purchasingModels = PurchasingRequests::query()->where('status_id', 1)->get();
        $productTypes = [];
        $comments = '';

        $purchasingProducts = PurchasingProduct::with(['product'])
            ->whereHas('purchasingRequest',function ($query) {
                $query->where('status_id', 1);
            })->groupBy('product_id')
            ->orderBy('product_id', 'asc')
            ->select('product_id', DB::raw('SUM(count)'))
            ->get();

        foreach ($purchasingProducts as $key =>$purchasingProduct) {
            if ($purchasingProduct->product->type->name) {
                $productTypes[$purchasingProduct->product->type->name][$key]['product'] = $purchasingProduct->product;
                $productTypes[$purchasingProduct->product->type->name][$key]['count'] = $purchasingProduct->sum;
            }
        }

        foreach ($purchasingModels as $purchasingModel) {
            if (!empty(trim($purchasingModel->list))) $comments .= $purchasingModel->list . ', ';
        }

        $data = [
            'productTypes' => $productTypes,
            'comments' => $comments,
        ];;
        return view('purchasing.list', $data);
    }
}
