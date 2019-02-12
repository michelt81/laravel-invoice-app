<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Invoice;
use App\InvoiceItem;
use App\Product;
use App\Usergroup;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{

    /**
     * @var Usergroup
     */
    private $_usergroup;

    /**
     * @var bool Use WKHTMLPDF or not
     */
    private $_helperPdf = true;

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
        $invoices = $this->_usergroup->invoices()->with('customer')
            ->latest()
            ->paginate(15);

        return view('invoice.index', [
            'title' => trans('strings.invoices'),
            'invoices' => $invoices
        ]);
    }
    public function setAsPaid(Invoice $invoice)
    {
        $invoice->status = 'paid';
        $invoice->save();
        // TODO send notification email to customer?
        return redirect()
            ->route('invoice.index')
            ->withInfo(
                trans(
                    'messages.invoice_paid',
                    ['invoice_number' => $invoice->invoice_number]
                )
            );
    }

    public function setAsPending(Invoice $invoice)
    {
        $invoice->status = 'pending';
        $invoice->save();
        return redirect()
            ->route('invoice.index')
            ->withInfo(
                trans(
                    'messages.invoice_pending',
                    ['invoice_number' => $invoice->invoice_number]
                )
            );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        // $customers = $this->_usergroup->customers()->pluck('name', 'id');
        $customers = $this->_usergroup->customers()->select('id', 'name', 'email')->get()->getDictionary();

        $products = $this->_usergroup->products;

        $taxRates = $this->_usergroup->getTaxRatesForSelect();

        // add missing rate(s) to dropdown
        foreach ($products as $product) {
            if (!in_array($product->tax_rate, $taxRates)) {
                $taxRates[$product->tax_rate] = mfrmt($product->tax_rate);
            }
        }

        $taxRateTotals = array_fill_keys(array_keys($taxRates), 0);

        return view('invoice.create', [
            'title' => trans('strings.create_invoice'),
            'customers' => $customers,
            'products' => $products,
            'customer_id' => $request->session()->pull('customer_id'),
            'taxRates' => $taxRates,
            'taxRateTotals' => $taxRateTotals
        ]);
    }

    public function pdf (Invoice $invoice, Request $request)
    {
        if ($invoice->usergroup_id != $this->_usergroup->id) {
            throw new NotFoundHTTPException;
        }

        $data = [
            'invoice' => $invoice,
            'taxRates' => $invoice->getTaxRates(),
        ];



        if ($this->_helperPdf) {
            $pdf = PDF::loadView('pdf.invoice', $data);
            return $pdf->download('invoice.pdf');
        } else {
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView('pdf.invoice', $data);
            return $pdf->download('invoice.pdf'); // or stream()
        }

        return view('pdf.invoice', $data);
    }

    /**
     * Send an invoice e-mail reminder to customer.
     *
     * @param  Request  $request
     * @param  Invoice  $invoice
     * @return Response
     */
    public function sendInvoiceEmail(Request $request, Invoice $invoice)
    {
        Mail::send('emails.invoice', ['invoice' => $invoice], function ($m) use ($invoice) {

            $data = [
                'invoice' => $invoice,
                'taxRates' => $invoice->getTaxRates(),
            ];
            if ($this->_helperPdf) {
                $pdf = PDF::loadView('pdf.invoice', $data);
            } else {
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadView('pdf.invoice', $data);
            }
            $m->attachData($pdf->output(), 'invoice.pdf');


            $m->to($invoice->customer->email, $invoice->customer->name)
                ->subject('Your invoice #' . $invoice->invoice_number);
        });

        $invoice->update([
            'send_date' => Carbon::now()
        ]);

        return redirect()
            ->route('invoice.index')
            ->withInfo(
                trans(
                    'messages.invoice_emailed',
                    ['invoice_number' => $invoice->invoice_number, 'email' => $invoice->customer->email]
                )
            );
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
            'customer_id' => 'required',
        ]);

        // get desired invoice start number
        $company = $this->_usergroup;

        // create invoice
        $customer_id = $request->input('customer_id');
        if (!$customer_id) {
            $customer_id = null;
        }
        if (!$customer_id) {
            $invoiceNumber = null;
        }

        $invoice = new Invoice([
            'customer_id' => $customer_id,
            'created_by' => Auth::id(),
            'status' => $customer_id ? 'pending' : 'concept'
        ]);

        $this->_usergroup->invoices()->save($invoice);

        $invoiceItems = $request->get('items', []);

        foreach ($invoiceItems as $itemData) {

            if (! isset($itemData['choose_product']) || ! $itemData['choose_product']) {
                $itemData['product_id'] = null;
            } else {
                // lookup product name
                $product = $this->_usergroup->products()
                    ->select('name')
                    ->find($itemData['product_id']);
                $itemData['name'] = $product->name;
            }

            if (isset($itemData['save_new']) && $itemData['save_new']) {
                $product = new Product([
                    'name' => $itemData['name'],
                    'price' => $itemData['unit_price'],
                    'tax_rate' => $itemData['tax_rate'],
                ]);
                $company->products()->save($product);
            }

            $invoiceItem = new InvoiceItem([
                'product_id' => $itemData['product_id'],
                'name' => $itemData['name'],
                'units' => $itemData['quantity'],
                'price' => $itemData['unit_price'],
                'tax_rate' => $itemData['tax_rate'],
            ]);

            $invoice->items()->save($invoiceItem);

        }
        $saveAction = $request->get('save_action');
        switch ($saveAction) {
            case 'pdf': {
                return redirect()
                    ->route('invoice.pdf', $invoice->id);
                break;
            }
            case 'email': {
                return redirect()
                    ->route('invoice.email', $invoice->id);
                break;
            }
            default: {
                return redirect()
                    ->route('invoice.index')
                    ->withInfo('Invoice ' . $invoice->invoice_number . ' has been created');
            }
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

        $invoice = $this->_usergroup->invoices()->findOrFail($id);

        $data = [
            'invoice' => $invoice,
            'taxRates' => $invoice->getTaxRates(),
        ];

        return view('invoice.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Invoice $invoice)
    {
        // $customers = $this->_usergroup->customers()->pluck('name', 'id');
        $customers = $this->_usergroup->customers()->select('id', 'name', 'email')->get()->getDictionary();

        if ($invoice->send_date->timestamp > 0) {
            return redirect()
                ->route('invoice.show', $invoice->id)
                ->withInfo(trans('messages.invoice_view_only'));
        }

        $products = $this->_usergroup->products;

        $taxRates = $this->_usergroup->getTaxRatesForSelect();

        // add missing rate(s) to dropdown
        foreach ($products as $product) {
            if (!in_array($product->tax_rate, $taxRates)) {
                $taxRates[$product->tax_rate] = mfrmt($product->tax_rate);
            }
        }

        $taxRateTotals = array_fill_keys(array_keys($taxRates), 0);

        return view('invoice.edit', [
            'invoice' => $invoice,
            'title' => trans('strings.edit_invoice'),
            'customers' => $customers,
            'products' => $products,
            'customer_id' => $request->session()->pull('customer_id'),
            'taxRates' => $taxRates,
            'taxRateTotals' => $taxRateTotals
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {

        // get desired invoice start number
        $company = $this->_usergroup;

        if ($invoice->usergroup_id != $this->_usergroup->id) {
            die('No auth');
        }

        // create invoice
        $customer_id = $request->input('customer_id');
        if (!$customer_id) {
            $customer_id = null;
        }
        if (!$customer_id) {
            $invoiceNumber = null;
        }

        $invoice->customer_id = $customer_id;
        $invoice->created_by = Auth::id();
        $invoice->status = $customer_id ? 'pending' : 'concept';

        $invoice->save();

        // clear out existing items
        $invoice->items()->delete();

        $invoiceItems = $request->get('items', []);

        foreach ($invoiceItems as $itemData) {

            if (! isset($itemData['choose_product']) || ! $itemData['choose_product']) {
                $itemData['product_id'] = null;
            } else {
                // lookup product name
                $product = $this->_usergroup->products()
                    ->select('name')
                    ->find($itemData['product_id']);
                $itemData['name'] = $product->name;
            }

            if (isset($itemData['save_new']) && $itemData['save_new']) {
                $product = new Product([
                    'name' => $itemData['name'],
                    'price' => $itemData['unit_price'],
                    'tax_rate' => $itemData['tax_rate'],
                ]);
                $company->products()->save($product);
            }

            $invoiceItem = new InvoiceItem([
                'product_id' => $itemData['product_id'],
                'name' => $itemData['name'],
                'units' => $itemData['quantity'],
                'price' => $itemData['unit_price'],
                'tax_rate' => $itemData['tax_rate'],
            ]);

            $invoice->items()->save($invoiceItem);

            // save the stats key
            $invoice->total = $invoice->getTotalAttribute();
            $invoice->save();

        }


        $saveAction = $request->get('save_action');
        switch ($saveAction) {
            case 'pdf': {
                return redirect()
                    ->route('invoice.pdf', $invoice->id);
                break;
            }
            case 'email': {
                return redirect()
                    ->route('invoice.email', $invoice->id);
                break;
            }
            default: {
                return redirect()
                    ->route('invoice.index')
                    ->withInfo('Invoice ' . $invoice->invoice_number . ' has been created');
            }
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
        $item = $this->_usergroup->invoices()->findOrFail($id);
        $item->delete();

        return redirect()->route('invoice.index')->withInfo(
            trans('messages.invoice_deleted', ['invoice_number' => $item->invoice_number])
        );
    }
}
