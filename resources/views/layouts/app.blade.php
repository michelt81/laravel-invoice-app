<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@if(isset($title)) {{ $title }} || @endif Invoice App Online factureren</title>

    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="{{ asset('css/global.css?v=4') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700' rel='stylesheet' type='text/css'>
    <link href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css" rel="stylesheet" type="text/css">

    {{--Custom colors for logged in company (usergroup)--}}

    @if(isset(\Auth::user()->usergroup->primary_color))
    <style>

        {{--Primary color--}}
        footer, .btn, .btn:hover {
            background: #{{ \Auth::user()->usergroup->primary_color }};
        }
        h1, h2, h3, h4, h5, h6, .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus {
            color: #{{ \Auth::user()->usergroup->primary_color }};
        }

        {{--Secondary color--}}
        a, a:hover {
            color: #{{ \Auth::user()->usergroup->secondary_color }};
        }
    </style>
    @endif
</head>
<body>

    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" title="Home" href="/"><img alt="invoice - online factureren" src="{{ asset('images/logo.jpg') }}" width="302" height="120"></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    @if(\Auth::check())
                        <li class="{{ \Request::url() === url('/home') ? "active" : "" }}"><a href="{{ url('/home') }}">Dashboard</a></li>
                        <li class="{{ \Request::is('invoice') || \Request::is('invoice/*') ? "active" : "" }}"><a href="{{ url('/invoice') }}">{{ trans('strings.invoices') }}</a></li>
                        <li class="{{ \Request::is('customer') || \Request::is('customer/*') ? "active" : "" }}">{{ link_to_route('customer.index', trans('strings.customers')) }}</li>
                        <li class="{{ \Request::is('product') || \Request::is('product/*') ? "active" : "" }}">{{ link_to_route('product.index', trans('strings.products')) }}</li>
                        <li class="{{ \Request::is('usergroup') || \Request::is('usergroup/*/edit') ? "active" : "" }}">
                            {{ link_to_route('usergroup.edit', trans('strings.settings'), ['id' => Auth::id()]) }}
                        </li>
                        @can('index-usergroup')
                            <li class="{{ \Request::is('usergroup') || \Request::is('usergroup') ? "active" : "" }}">
                                {{ link_to_route('usergroup.index', trans('strings.usergroups')) }}
                            </li>
                        @endcan
                        <li>
                            <a href="/logout">
                                <i class="fa fa-sign-out fa-lg"></i> Logout
                            </a>
                        </li>
                    @else
                        <li class="{{ \Request::url() === url('/') ? "active" : "" }}"><a href="{{ url('/') }}">Home</a></li>
                        <li class="{{ \Request::url() === url('/login') ? "active" : "" }}"><a href="{{ url('/login') }}">{{ trans('strings.login') }}</a></li>
                        <li class="{{ \Request::url() === url('/register') ? "active" : "" }}"><a href="{{ url('/register') }}">{{ trans('strings.register') }}</a></li>
                    @endif
                </ul>

            </div><!--/.nav-collapse -->
        </div>
    </nav>

    @if (Session::has('info'))
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info alert-block">
                    {{ Session::get('info') }}
                </div>
            </div>
        </div>
    </div>
    @endif

    @yield('content')

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h5 class="text-uppercase">Invoice App</h5>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <p>
                        <strong>Invoice</strong><br>
                        contact

                    </p>
                </div>
                <div class="col-md-4">
                    <p>
                        <strong>{{ trans('strings.address') }}</strong><br>

                    </p>
                </div>
                <div class="col-md-4">
                    <p>
                        <strong>Feedback</strong><br>

                </div>
            </div>

        </div>
    </footer>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('js/jquery-1.11.2.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/locales/nl-nl.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-price-format/2.1/jquery.priceformat.min.js"></script>
    <script src="{{ asset('js/locales/' . config('app.money_locale') . '.js') }}"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="{{ asset('js/modernizr.custom.js') }}"></script>
    <script src="{{ asset('js/script.js?v=2') }}"></script>
    @yield('jquery')
</body>
</html>