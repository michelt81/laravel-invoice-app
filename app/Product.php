<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;

class Product extends Model
{

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'tax_rate',
    ];


    /**
     * Set the price.
     *
     * @param  string  $value
     * @return void
     */
    public function setPriceAttribute($value)
    {
        $formatter = new \NumberFormatter(
            config('app.money_locale'),
            \NumberFormatter::DECIMAL // format with decimal notation
        );

        $this->attributes['price'] = $formatter->parse(
            $value,
            \NumberFormatter::TYPE_DOUBLE // parse as float
        );

    }

}
