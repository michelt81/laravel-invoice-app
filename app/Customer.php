<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    const DEFAULT_COUNTRY_ID = 528;

    public static $createRules = array(
        'name' => 'required',
        'email' => 'required|email|unique:customers',
        'country_id' => 'required|exists:countries,id'
    );

    public static $updateRules = array(
        'name' => 'required',
        'email' => 'required|email',
        'country_id' => 'required|exists:countries,id'
    );

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'account_number',
        'address',
        'tax_number',
        'country_id',
        'zip',
        'city',
        'contact_person',
    ];

    /**
     * Get the comments for the blog post.
     */
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

}
