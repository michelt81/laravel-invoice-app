@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-md-12 text-center">
                <h1>{{ trans('strings.edit_product') }}</h1>
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
                    'route' => 'product.store',
                    'class' => 'bottomPadding'
                ]) !!}

                <div class="form-group">
                    {!! Form::label('name', trans('strings.name') . ':', ['class' => 'control-label']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('price', trans('strings.price') . ':', ['class' => 'control-label']) !!}
                    <div class="input-group">
                        <span class="input-group-addon">&euro;</span>
                        {!! Form::text('price', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('tax_rate', trans('strings.tax_rate') . ':', ['class' => 'control-label']) !!}
                    <div class="input-group">
                        <span class="input-group-addon">%</span>
                        {{ Form::select('tax_rate', $tax_rates, null, ['class' => 'form-control']) }}
                    </div>
                </div>


                {!! Form::submit(trans('strings.create_new_product'), ['class' => 'btn btn-default']) !!}

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection
