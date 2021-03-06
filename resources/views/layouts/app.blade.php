<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A student-centric house search website that makes finding your student house easy.">
    <meta name="author" content="Sam Williams">

    <title>StuPad - Student Housing Search</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:site_name" content="StuPad"/>
    <meta property="fb:app_id" content="151197575489333"/>
    <meta name="twitter:domain" content="StuPad.co.uk">
    <meta name="twitter:site" content="StuPad.co.uk">
@stack('seotags')

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/manifest.json">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#d74236">
    <link rel="shortcut icon" href="/favicon/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="StuPad">
    <meta name="application-name" content="StuPad">
    <meta name="msapplication-config" content="/favicon/browserconfig.xml">
    <meta name="theme-color" content="#d74236">

    <!-- jQuery -->
    <script src="{{asset("js/jquery-3.2.1.min.js")}}"></script>
    <script src="{{asset("js/jquery-ui.js")}}"></script>
    <link href="{{asset("css/jquery-ui.css")}}" rel="stylesheet">

    <!-- Bootstrap Core CSS & JavaScript -->
    <script src="{{asset("vendor/bootstrap/js/bootstrap.min.js")}}"></script>
    <link href="{{asset("vendor/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet">

    <!-- Plugin CSS & JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="{{asset("vendor/scrollreveal/scrollreveal.min.js")}}"></script>
    <script src="{{asset("vendor/magnific-popup/jquery.magnific-popup.min.js")}}"></script>
    <link href="{{asset("vendor/magnific-popup/magnific-popup.css")}}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="{{asset('js/jquery.fancybox.min.js')}}"></script>
    <link href="{{asset('css/jquery.fancybox.min.css')}}" rel="stylesheet">


    <!-- Theme CSS & JavaScript -->
    <script src="{{asset("js/creative.min.js")}}"></script>
    <link href="{{asset("css/creative.css")}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{asset("vendor/font-awesome/css/font-awesome.min.css")}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body id="page-top">
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-opaque">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="{{ route('home') }}"><span class="stu">STU</span><span class="pad">PAD</span></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a class="page-scroll nav-btn" style="margin-right: 20px" href="{{ route('search') }}">Search</a></li>
                    <li>
                        <a class="page-scroll nav-btn" href="{{ route('login') }}">Log In</a>
                    </li>
                    <li>
                        <a class="page-scroll nav-btn nav-btn-muted" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <li><a class="page-scroll nav-btn" href="{{ route('search') }}">Search</a></li>
                    <li><a class="page-scroll nav-btn" href="{{ Auth::user()->landlord ? route('mylistings') : route('savedlistings') }}">Dashboard</a></li>
                    <li>
                        <a class="page-scroll nav-btn nav-btn-muted" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Log Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

</body>
@include('scripts.disabledraggable')
@include('scripts.facebooksdk')
</html>