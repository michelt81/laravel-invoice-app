@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-md-12 text-center">
                <h1>{{ trans('strings.customers') }} {{ link_to_route('customer.create', trans('strings.create'), [], ['class' => 'btn btn-default']) }}</h1>
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ trans('strings.name') }}</th>
                        <th>{{ trans('strings.email') }}</th>
                        <th>{{ trans('strings.account_number') }}</th>
                        <th>{{ trans('strings.address') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->account_number }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>
                            {{ link_to_route('customer.edit', trans('strings.edit'), $customer->id) }}

                            {!! Form::open(['method' => 'DELETE', 'route' => ['customer.destroy', $customer->id]]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('strings.delete'), ['type' => 'submit', 'class' => 'btn btn-link trash']) !!}
                            {!! Form::close() !!}

                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row text-center">
            {{ $customers->links() }}
        </div>
    </div>
@endsection