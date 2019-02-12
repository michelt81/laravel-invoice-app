<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'name',
        'units',
        'price',
        'tax_rate',
        'discount',
    ];

    public function getSubTotalAttribute()
    {
        return $this->units * $this->price - $this->discount;
    }

    public function getTaxAttribute()
    {
        return $this->getSubTotalAttribute() * $this->tax_rate / 100;
    }

    public function getTotalAttribute()
    {
        return $this->getSubTotalAttribute() + $this->getTaxAttribute();
    }

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
