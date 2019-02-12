@extends('layouts.app')

@section('content')

    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        {!! Form::model($usergroup, [
            'method' => 'PATCH',
            'route' => ['usergroup.update', $usergroup->id],
            'files' => true,
            'class' => 'bottomPadding'
        ]) !!}
        <div class="row">
            <div class="col-md-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#company-settings" aria-controls="company-settings" role="tab" data-toggle="tab">{{ trans('strings.company_user_settings') }}</a></li>
                    <li role="presentation"><a href="#general-settings" aria-controls="general-settings" role="tab" data-toggle="tab">{{ trans('strings.general_settings') }}</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content" style="margin-top: 20px;">
                    <div role="tabpanel" class="tab-pane active" id="company-settings">

                        <div class="row form-group">
                            <div class="col-xs-6">
                                {!! Form::label(trans('company_logo')) !!}
                                {!! Form::file('logo', null) !!}
                            </div>
                            <div class="col-xs-6">
                                <img class="img-responsive" src="{{ $usergroup->logo }}" alt="Logo">
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('company', trans('strings.name') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('company', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('address', trans('strings.address') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('address', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('address2', trans('strings.address2') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('address2', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('postal_code', trans('strings.postal_code') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('postal_code', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('country', trans('strings.country') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('country', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('kvk', trans('strings.kvk') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('kvk', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('vat_number', trans('strings.tax_number') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('vat_number', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('iban', trans('strings.iban') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('iban', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="general-settings">

                        <div class="form-group">
                            {!! Form::label('invoice_condition_days', trans('strings.payment_condition_invoice') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('invoice_condition_days', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('invoice_condition_reminder', trans('strings.payment_condition_invoice_reminder') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('invoice_condition_reminder', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('invoice_start', trans('strings.invoice_count_start_number') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('invoice_start', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('tax_high', trans('strings.tax_high') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('tax_high', mfrmt($usergroup->tax_high), ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('tax_low', trans('strings.tax_low') . ':', ['class' => 'control-label']) !!}
                            {!! Form::text('tax_low', mfrmt($usergroup->tax_low), ['class' => 'form-control', 'required' => 'required']) !!}
                        </div>


                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox('update_product_taxes', 1, true) }} {{ trans('strings.update_product_taxes') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                {!! Form::submit(trans('strings.update_settings'), ['class' => 'btn btn-default btn-lg']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>

@endsection
