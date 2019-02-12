<?php

namespace App\Http\Controllers;

use App\User;
use App\Usergroup;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;

class UsergroupController extends Controller
{
    /**
     * UsergroupController constructor.
     */
    public function __construct()
    {
        // User has to be logged in
        $this->middleware('auth');
        // User has to be able to admin group (group admin or superadmin)
        $this->middleware('IsGroupAdmin', ['only' => [
            'show',
            'edit',
            'update',
            'destroy'
        ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->user()->cannot('index-usergroup')) {
            abort(403);
        }

        //Show all companies (usergroups); only for superadmin
        $usergroups = Usergroup::latest()->paginate(15);
        return view('usergroup.index')->withUsergroups($usergroups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Create a new usergroup(company) -- maybe add a register page for new clients to register themselves?
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Create a new usergroup(company) -- maybe add a register page for new clients to register themselves?
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //View a usergroup -- superadmin only
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Edit a usergroup -- superadmin only
        $usergroup = Usergroup::findOrFail($id);
        return view('usergroup.edit')->withUsergroup($usergroup);
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
        //Edit a usergroup -- superadmin only
        $usergroup = Usergroup::findOrFail($id);

        $this->validate($request, [
            'company' => 'required',
            'tax_low' => 'required', // |numeric|between:0,100',
            'tax_high' => 'required', // |numeric|between:0,100',
            'image' => 'mimes:jpeg,bmp,png',
        ]);

        $input = $request->all();

        $updateProductRates = $request->get('update_product_taxes');
        if ($updateProductRates) {
            $oldTaxLow = $usergroup->tax_low;
            $oldTaxHigh = $usergroup->tax_high;
        }

        $usergroup->fill($input);

        if(Input::file()) {
            $image = Input::file('logo');
            $filename  = $usergroup->id . '_' . time() . '.' . $image->getClientOriginalExtension();

            $path = public_path('images/logos/' . $filename);

            // height 400, width: auto
            Image::make($image->getRealPath())
                ->resize(null, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);

            $usergroup->logo = $filename;
        }

        $usergroup->save();

        if ($updateProductRates) {
            $usergroup->products()->where('tax_rate', $oldTaxLow)->update(['tax_rate' => $usergroup->tax_low]);
            $usergroup->products()->where('tax_rate', $oldTaxHigh)->update(['tax_rate' => $usergroup->tax_high]);
        }


        return redirect()->back()->withInfo(trans('messages.company_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Delete a usergroup -- superadmin only
        $item = Usergroup::findOrFail($id);
        $item->delete();

        return redirect()->route('usergroup.index');
    }
}
