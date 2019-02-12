<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductPresenter;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    /**
     * @var Usergroup
     */
    private $_usergroup;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        if (Auth::check()) {
            $this->_usergroup = Auth::user()->usergroup;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->_usergroup->products()->latest()->paginate(15);

        return view('product.index', [
            'products' => $products,
            'title' => trans('strings.products')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $usergroup = $request->user()->usergroup;
        $taxRates = [
            $usergroup->tax_high => mfrmt($usergroup->tax_high),
            $usergroup->tax_low => mfrmt($usergroup->tax_low),
        ];
        // if current (or zero) tax rate is not set, add it to array so that it can be kept while updating
        return view('product.create', [
            'tax_rates' => $taxRates
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:products',
            'price' => 'required|regex:/^[0-9]{1,3}(\.[0-9]{3})*(,[0-9]+)*$/',
            'tax_rate' => 'required|numeric|between:0,100'
        ]);

        $product = new Product(Input::all());

        $this->_usergroup->products()->save($product);

        return redirect()->route('product.index');
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
        $product = $this->_usergroup->products()->find($id);

        $usergroup = $this->_usergroup;
        $taxRates = [
            $usergroup->tax_high => mfrmt($usergroup->tax_high),
            $usergroup->tax_low => mfrmt($usergroup->tax_low),
        ];

        return view('product.edit', [
            'product' => $product,
            'tax_rates' => $taxRates
        ]);
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
        $product = $this->_usergroup->products()->findOrFail($id);

        $this->validate($request, [
            // 'name' => 'required|unique:products',
            'name' => sprintf('required|unique:products,name,%d,id,usergroup_id,%d',
                $product->id, $this->_usergroup->id),
            'price' => 'required', // |regex:/^[0-9]{1,3}(\.[0-9]{3})*(,[0-9]+)*$/',
            'tax_rate' => 'required|numeric|between:0,100'
        ]);

        $product->update($request->all());

        if ($request->ajax()) {
            return response()->json($product);
        } else {
            return redirect()->route('product.index')
                ->withInfo(trans('messages.product_updated'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Product::findOrFail($id);
        $item->delete();

        return redirect()->route('product.index');
    }
}
