@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-md-12 text-center">
                <h1>{{ trans('strings.usergroups') }}</h1>
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ trans('strings.name') }}</th>
                        <th>{{ trans('strings.invoice_count') }}</th>
                        <th>{{ trans('strings.all_invoices_total') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($usergroups as $usergroup)
                        <tr>
                            <td>{{ $usergroup->company }}</td>
                            <td>{{ $usergroup->invoices()->count() }}</td>
                            <td>{{ mfrmt($usergroup->invoices()->sum('total')) }}</td>
                            <td>
                                {{ link_to_route('usergroup.edit', trans('strings.edit'), $usergroup->id) }}

                                {!! Form::open(['method' => 'DELETE', 'route' => ['usergroup.destroy', $usergroup->id]]) !!}
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
            {{ $usergroups->links() }}
        </div>
    </div>
@endsection

