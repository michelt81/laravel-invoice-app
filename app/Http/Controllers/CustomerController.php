<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Customer;
use Illuminate\Session\SessionInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Webpatser\Countries\Countries;
use Webpatser\Countries\CountriesFacade;

class CustomerController extends Controller
{

    const COUNTRIES_EUROPE_GROUP_ID = 150;

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
        $customers = $this->_usergroup->customers()->latest()->paginate(15);

        return view('customer.index', [
            'customers' => $customers,
            'title' => trans('strings.customers')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //$request = Request::capture();
        $for = $request->input('for');
        if ($for) {
            $request->session()->put('customer_for', $for);
        }

        return view('customer.create', [
            'countries' => $this->getCountries()
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
            'name' => 'required',
            // email is unique inside customers of company, not site-wide
            'email' => sprintf('required|email|unique:customers,email,NULL,id,usergroup_id,%d',
                $this->_usergroup->id),
            'country_id' => 'required|exists:countries,id'
        ]);

        $customer = new Customer($request->all());

        $this->_usergroup->customers()->save($customer);

        $for = $request->session()->pull('customer_for');

        if ('invoice' == $for) {
            return redirect()->route('invoice.create')
                ->with('customer_id', $customer->id);
        } else {
            return redirect()->route('customer.index');
        }

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
        $customer = $this->_usergroup->customers()->find($id);

        return view('customer.edit', [
            'customer' => $customer,
            'countries' => $this->getCountries(),
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
        $customer = $this->_usergroup->customers()->findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            // email is unique inside customers of company, not site-wide
            'email' => sprintf('required|email|unique:customers,email,%d,id,usergroup_id,%d',
                $customer->id, $this->_usergroup->id),
            'country_id' => 'required|exists:countries,id'
        ]);

        $customer->update($request->all());

        if ($request->ajax()) {
            return response()->json($customer);
        } else {
            return redirect()->route('customer.index')
                ->withInfo(trans('messages.customer_updated'));
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
        $item = $this->_usergroup->customers()->findOrFail($id);
        $item->delete();

        return redirect()->route('customer.index');
    }
    /**
     * Get array of countries form db and translate them
     *
     * @return array $countries
     */
    private function getCountries(){
        $newCountries = array();
        $countries = Countries::where('region_code', self::COUNTRIES_EUROPE_GROUP_ID)->pluck('name', 'id');
        // nederland 528 belgie 56 luxemburg 442
        foreach($countries as $key => $country){
            if($key == 528 || $key == 56 )
            $newCountries[$key] = trans('countries.'.$country);
        }
        return $newCountries;
    }
}
