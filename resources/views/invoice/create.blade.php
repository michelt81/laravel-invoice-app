@extends('layouts.app')

@section('content')
    <style>
        .product_id {
            display: none;
        }
        input.quantity, input.unit_price, input.item_total {
            width: 100px !important;
        }
        @if (!count($products))
        .choose_product_w { display: none !important; }
        @endif

    </style>
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-md-12 text-center">
                <h1>{{ trans('strings.new_invoice') }}</h1>
            </div>
        </div>
        {!! Form::open([
            'id' => 'invoice',
            'route' => 'invoice.store',
            'class' => 'form-inline'
        ]) !!}
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="form-group" style="padding-bottom: 10px;">
                    {!! Form::Label('customer_id', trans('strings.customer').':') !!}
                    {!!
                        Form::dataSelect(
                        'customer_id',
                        $customers,
                        $customer_id,
                        array(
                            'class' => 'form-control',
                            'placeholder' => trans('strings.select_customer'),
                            'required' => 'required'
                        ),
                        'id', 'name'
                        )
                    !!}
                    <a href="/customer/create?for=invoice">{{ trans('strings.customer_create') }}</a>
                </div>
            </div>
        </div>
        <div class="row invoice-item">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 text-center">
                            <div class="form-group">
                                <label class="sr-only" for="quantity-0">{{ trans('strings.quantity') }}</label>
                                <div class="input-group">
                                    <input required="required" name="items[0][quantity]" type="number" class="form-control quantity" id="quantity-0" placeholder="{{ trans('strings.quantity') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="description">{{ trans('strings.product_name') }}</label>
                                <input required="required" name="items[0][name]" type="text" class="product_name form-control" id="product_name-0" placeholder="{{ trans('strings.product_name') }}"></input>
                                {!!
                                 Form::dataSelect(
                                    "items[0][product_id]",
                                    $products,
                                    null,
                                    ['class' => 'product_id form-control', 'placeholder' => trans('strings.choose_product')],
                                    'id', 'name'
                                    )
                                !!}
                            </div>

                            <div class="form-group">
                                <label class="sr-only" for="unit_price-0">{{ trans('strings.unit_price') }}</label>
                                <div class="input-group">
                                    <div class="input-group-addon">€</div>
                                    <input required="required" name="items[0][unit_price]" type="text" class="unit_price form-control" id="unit_price-0" placeholder="{{ trans('strings.amount') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">{{ trans('strings.tax_rate') }}, %</div>
                                    {!! Form::select('items[0][tax_rate]', $taxRates, null, ['class' => 'form-control tax_rate', 'required' => 'required']) !!}

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="sr-only" for="item_total-0">{{ trans('strings.item_total') }}</label>
                                <div class="input-group">
                                    <div class="input-group-addon">€</div>
                                    <input readonly disabled name="items[0][item_total]" type="text" class="item_total form-control" id="item_total-0" placeholder="{{ trans('strings.amount') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="btn-toolbar">
                                    <button type="button" class="btn btn-default-outline remove-item">-</button>
                                    <button type="button" class="btn btn-default-outline add-item">+</button>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-2 col-md-12">
                        <div class="form-group col-md-3">
                            <div class="checkbox choose_product_w">
                                <label style="font-size: 12px;">
                                    <input name="items[0][choose_product]" class="choose_product" type="checkbox" value="1"> {{ trans('strings.choose_existing') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-lg-7 col-md-7 col-sm-7 col-xs-12">
                            <div class="checkbox save_new_div">
                                <label style="font-size: 12px;">
                                    <input class="save_new" name="items[0][save_new]" type="checkbox" value="1"> {{ trans('strings.save_as_new') }}
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-7 col-md-5 text-center">
                <div class="well text-left">

                    Subtotal   :  € <span id="subtotal">0</span><br />
                    @foreach ($taxRates as $value => $display)
                        Tax Rate {{ $display }}% : &euro; <span data-rate="{{ $value }}" class="tax-rate-total">0</span><br />
                    @endforeach

                            Total:          &euro; <span id="total">0</span><br />

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="btn-toolbar">
                    {!! Form::button(trans('strings.save_pdf'), ['class' => 'btn btn-default', 'type' => 'submit', 'name' => 'save_action', 'value' => 'pdf']) !!}
                    {!! Form::button(trans('strings.email_invoice_to_customer'), [
                            'id' => 'email_save_action',
                            'class' => 'btn btn-default',
                            'type' => 'submit',
                            'name' => 'save_action',
                            'value' => 'email',
                            'data-token' => csrf_token(),
                        ]) !!}
                    {!! Form::button(trans('strings.create_invoice'), ['class' => 'btn btn-default', 'type' => 'submit']) !!}
                </div>
            </div>
        </div>


        {!! Form::close() !!}
    </div>

@endsection

@section('jquery')
    <script>
        var products = {!! json_encode($products->getDictionary()) !!};
        var taxRateTotals = {!! json_encode($taxRateTotals) !!};
    </script>
    <script src="{{ asset('js/invoice.js?v=3') }}"></script>
@endsection