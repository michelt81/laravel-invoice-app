<?php

namespace App\Observers;

use App\Invoice;

class InvoiceObserver
{
    /**
     * Listen to the Invoice created event.
     *
     * @param  Invoice  $invoice
     * @return void
     */
    public function created(Invoice $invoice)
    {
        //
    }

    /**
     * Listen to the Invoice deleting event.
     *
     * @param  Invoice  $invoice
     * @return void
     */
    public function deleting(Invoice $invoice)
    {
        //
    }

    /**
     * Listen to the Invoice deleting event.
     *
     * @param  Invoice  $invoice
     * @return void
     */
    public function creating(Invoice $invoice)
    {
        $usergroup = $invoice->usergroup;

        $desiredInvoiceStart = intval($usergroup->invoice_start);

        // get max existing invoice number (from latest)
        $lastInvoice = $usergroup->invoices()->whereNotNull('invoice_number')->orderBy('created_at', 'desc')->first();

        if ($lastInvoice) {

            // if last invoice invoice # > than desired, then use +1 from it,
            // otherwise use the desired
            $lastInvoiceNumber = intval($lastInvoice->invoice_number);

            if ($lastInvoiceNumber >= $desiredInvoiceStart) {
                $invoiceNumber = $lastInvoiceNumber + 1;
            } else {
                $invoiceNumber = $desiredInvoiceStart;
            }
        } else {
            $invoiceNumber = $desiredInvoiceStart;
        }

        if (!$invoiceNumber) $invoiceNumber = 1;

        $invoice->invoice_number = $invoiceNumber;

        // update desired invoice start
        $usergroup->invoice_start = $invoiceNumber;
        $usergroup->save();

    }
}