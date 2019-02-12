@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-md-12 text-center">
                <h1>{{ trans('strings.create_new_customer') }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-md-12">


                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {!! Form::open([
                    'route' => 'customer.store',
                    'class' => 'bottomPadding'
                ]) !!}

                <div class="form-group">
                    {!! Form::label('name', trans('strings.name') . ':', ['class' => 'control-label']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', trans('strings.email') . ':', ['class' => 'control-label']) !!}
                    {!! Form::input('email', 'email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('contact_person', trans('strings.contact_person') . ':', ['class' => 'control-label']) !!}
                    {!! Form::text('contact_person', null, ['class' => 'form-control']) !!}
                </div>

                {{--
                <div class="form-group">
                    {!! Form::label('account_number', trans('strings.account_number') . ':', ['class' => 'control-label']) !!}
                    {!! Form::text('account_number', null, ['class' => 'form-control']) !!}
                </div>
                --}}

                <div class="form-group">
                    {!! Form::label('tax_number', trans('strings.tax_number') . ':', ['class' => 'control-label']) !!}
                    {!! Form::text('tax_number', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('country_id', trans('strings.country') . ':', ['class' => 'control-label']) !!}
                    {!! Form::select('country_id', $countries, \App\Customer::DEFAULT_COUNTRY_ID, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('city', trans('strings.city') . ':', ['class' => 'control-label']) !!}
                    {!! Form::text('city', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('zip', trans('strings.postal_code') . ':', ['class' => 'control-label']) !!}
                    {!! Form::text('zip', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('address', trans('strings.address') . ':', ['class' => 'control-label']) !!}
                    {!! Form::text('address', null, ['class' => 'form-control']) !!}
                </div>

                {!! Form::submit(trans('strings.create_new_customer'), ['class' => 'btn btn-default']) !!}

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection
