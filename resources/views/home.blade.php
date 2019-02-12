@extends('layouts.app')

@section('content')
<div class="container">
    {{--<div class="row">--}}
        {{--<div class="col-md-10 col-md-offset-1">--}}
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    {{ trans('messages.welcome') }} <em>{{ \Auth::user()->name }}</em>!

                    <a href="{{ url('/logout') }}" class="btn btn-default">{{ trans('strings.logout') }}</a>
                </div>
            </div>
        {{--</div>--}}
    {{--</div>--}}
</div>
@endsection
