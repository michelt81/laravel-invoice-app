@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-md-12 text-center">
                <h1>{{ trans('strings.invoices') }} {{ link_to_route('invoice.create', trans('strings.create'), [], ['class' => 'btn btn-default']) }}</h1>
                <table class="table">
                    <thead>

                    <tr>
                        <th>{{ trans('strings.date') }}</th>
                        <th>{{ trans('strings.invoice_number') }}</th>
                        <th>{{ trans('strings.customer') }}</th>
                        <th>{{ trans('strings.bill_status') }}</th>
                        <th>&nbsp;</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->created_at }}</td>
                        <td>{!! $invoice->invoice_number ? sprintf('%03d', $invoice->invoice_number) : ('<span class="empty">' . trans('strings.empty') . '</span>') !!}</td>
                        <td>{!! $invoice->customer ? e($invoice->customer->name) : ('<span class="empty">' . trans('strings.empty') . '</span>') !!}</td>
                        <td>{{ $invoice->status . (($invoice->send_date->timestamp > 0) ? sprintf(' (%s)', trans('strings.email_sent')) : '') }}</td>
                        <td>
                            @if ($invoice->send_date->timestamp <= 0)
                                {{ link_to_route('invoice.edit', trans('strings.edit'), $invoice->id) }}
                            @else
                                {{ link_to_route('invoice.show', trans('strings.view'), $invoice->id) }}
                            @endif
                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id]]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('strings.delete'), ['type' => 'submit', 'class' => 'btn btn-link trash']) !!}
                            {!! Form::close() !!}
                            @if ($invoice->customer && $invoice->customer->email)
                            {{ link_to_route('invoice.email', trans('strings.send_email'), $invoice->id) }}
                            @endif
                            @if ($invoice->status != 'paid')
                                <br>{{ link_to_route('invoice.set-as-paid', trans('strings.payment_received'), $invoice->id) }}
                            @endif
                            @if ($invoice->status != 'pending')
                                <br>{{ link_to_route('invoice.set-as-pending', trans('strings.set_to_pending'), $invoice->id) }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5">
                            {{ $invoices->links() }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
