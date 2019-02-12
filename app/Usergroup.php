<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usergroup extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company', 'tax_low', 'tax_high', 'logo', 'primary_color', 'secondary_color', 'postal_code', 'city', 'email', 'phone', 'fax', 'mobile_phone', 'iban', 'vat_number', 'kvk',
        'address', 'address2', 'country', 'invoice_start', 'invoice_condition_days', 'invoice_condition_reminder'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the users for the usergroup.
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * Get the users for the usergroup.
     */
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    /**
     * Get the products for the usergroup.
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * Get the customers for the usergroup.
     */
    public function customers()
    {
        return $this->hasMany('App\Customer');
    }

    /**
     * Set the price.
     *
     * @param  string  $value
     * @return void
     */
    public function setTaxHighAttribute($value)
    {
        $formatter = new \NumberFormatter(
            config('app.money_locale'),
            \NumberFormatter::DECIMAL // format with decimal notation
        );

        $this->attributes['tax_high'] = $formatter->parse(
            $value,
            \NumberFormatter::TYPE_DOUBLE // parse as float
        );

    }

    public function getTaxRatesForSelect()
    {
        return [
            $this->tax_high => mfrmt($this->tax_high),
            $this->tax_low => mfrmt($this->tax_low),
            0 => mfrmt(0),
        ];
    }

    public function getTaxRates()
    {
        return [
            $this->tax_high,
            $this->tax_low,
            0,
        ];
    }

    public function getLogoAttribute()
    {
        return $this->attributes['logo'] ? asset('images/logos/' . $this->attributes['logo']) : asset('images/1x1.png');
    }

}
