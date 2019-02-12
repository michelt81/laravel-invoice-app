@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">{{ trans('strings.invoice') }} {{ $invoice->invoice_number}}</h1>

                @if ($invoice->customer)
                    <h3>{{ trans('strings.customer') }}: {{ $invoice->customer->name }}</h3>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ trans('strings.quantity') }}</th>
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
                            <td>{{ $item->name }}</td>
                            <td>&euro; {{ mfrmt($item->price) }}</td>
                            <td>{{ mfrmt($item->tax_rate) }}%</td>
                            <td>&euro; {{ mfrmt($item->units * $item->price) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-6">
                <div class="well">
                    Subtotal   :  € <span id="subtotal">{{ mfrmt($invoice->subTotal) }}</span><br />
                    @foreach ($taxRates as $rate => $taxAmount)
                        Tax Rate {{ mfrmt($rate) }}% : € <span class="tax-rate-total">{{ mfrmt($taxAmount) }}</span><br />
                    @endforeach

                    Total:          € <span id="total">{{ mfrmt($invoice->total) }}</span><br />
                </div>
            </div>
        </div>
    </div>
@endsection