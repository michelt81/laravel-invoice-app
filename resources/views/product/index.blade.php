@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-md-12 text-center">
                <h1>{{ trans('strings.products') }} {{ link_to_route('product.create', trans('strings.create'), [], ['class' => 'btn btn-default']) }}</h1>
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ trans('strings.name') }}</th>
                        <th>{{ trans('strings.price') }}</th>
                        <th>{{ trans('strings.tax_rate') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ mfrmt($product->price) }}</td>
                            <td>{{ mfrmt($product->tax_rate) }}</td>
                            <td>
                                {{ link_to_route('product.edit', trans('strings.edit'), $product->id) }}

                                {!! Form::open(['method' => 'DELETE', 'route' => ['product.destroy', $product->id]]) !!}
                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('strings.delete'), ['type' => 'submit', 'class' => 'btn btn-link']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            {{ $products->links() }}
        </div>
    </div>
@endsection