<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'created_by',
        'invoice_number',
        'expire_date',
        'payment_condition_id',
        'status',
        'send_date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'send_date', 'expire_date'];

    /**
     * Get the customer that owns the invoice.
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    /**
     * Get the usergroup that owns the invoice.
     */
    public function usergroup()
    {
        return $this->belongsTo('App\Usergroup');
    }

    /**
     * Order entries for this inovoice
     */
    public function items()
    {
        return $this->hasMany('App\InvoiceItem');
    }

    public function getSubTotalAttribute()
    {
        $subTotal = 0;
        foreach ($this->items as $item) {
            $subTotal += $item->subTotal;
        }
        return $subTotal;
    }

    /**
     * Get taxes grouped by rates
     */
    public function getTaxRates()
    {
        $taxRates = [];
        foreach ($this->items as $item) {
            if ($item->units && $item->tax_rate > 0) {
                if (!isset($taxRates[$item->tax_rate])) {
                    $taxRates[$item->tax_rate] = 0;
                }
                $taxRates[$item->tax_rate] += $item->tax;
            }
        }
        return $taxRates;
    }

    public function getTotalAttribute()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->total;
        }
        return $total;
    }
}
