@extends('layouts.pdf')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-4 col-xs-offset-8">
                <strong>{{ $invoice->usergroup->company}}</strong><br>
                {{ $invoice->usergroup->address }}<br>
                {{ $invoice->usergroup->postal_code }} {{ $invoice->usergroup->city }}
            </div>
        </div>
        <div class="row">
            @if ($invoice->customer)
            <div class="col-xs-8">
                <b>{{ $invoice->customer->name }}</b><br>
                {{ $invoice->customer->address }}<br>
                {{ $invoice->customer->zip }} {{ $invoice->customer->city }}
            </div>
            @else
            <div class="col-xs-8">
                &nbsp;
            </div>
            @endif
            <div class="col-xs-4">
                {{ $invoice->usergroup->email }}</strong><br>
                {{ $invoice->phone }}
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 col-xs-offset-8">
                {{ trans('strings.kvk_short') }}: {{ $invoice->usergroup->kvk }}</strong><br>
                {{ trans('strings.tax_number_short') }}: {{ $invoice->usergroup->vat_number }}<br>
                {{ trans('strings.iban_short') }}: {{ $invoice->usergroup->iban }}
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-xs-9">
                <h2>{{ trans('strings.invoice') }} : {{ $invoice->invoice_number }}</h2>
            </div>
            <div class="col-xs-3">
                {{ trans('strings.invoice_date') }}: {{ $invoice->usergroup->kvk }}</strong><br>
                {{ trans('strings.expiration_date') }}: {{ $invoice->usergroup->vat_number }}
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <table class="table">
                    <thead>
                        <tr>
                            <th width="10">&nbsp;</th>
                            <th width="10">&nbsp;</th>
                            <th>{{ trans('strings.product_name') }}</th>
                            <th>{{ trans('strings.price') }}</th>
                            <th>{{ trans('strings.tax_rate') }}</th>
                            <th>{{ trans('strings.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->units }}</td>
                            <td>&times;</td>
                            <td>{{ $item->name }}</td>
                            <td>&euro; {{ mfrmt($item->price) }}</td>
                            <td>{{ mfrmt($item->tax_rate) }}%</td>
                            <td>&euro; {{ mfrmt($item->units * $item->price) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <th>{{ trans('strings.subtotal') }}</th>
                            <td>&euro; <span id="subtotal">{{ mfrmt($invoice->subTotal) }}</span></td>
                            <td>&nbsp;</td>
                        </tr>
                        @foreach ($taxRates as $rate => $taxAmount)
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <th>{{ mfrmt($rate) }}% {{ trans('strings.tax_rate') }} </th>
                            <td>&euro; <span class="tax-rate-total">{{ mfrmt($taxAmount) }}</span></td>
                            <td>&nbsp;</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <th>{{ trans('strings.total') }} </th>
                            <td>&euro; <span id="total">{{ mfrmt($invoice->total) }}</span></td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {{--
        <div class="footer">
            {{ trans('messages.default_invoice', array(
                'total_amount' => mfrmt($invoice->total),
            )) }}
        </div>
        --}}
    </div>
@endsection