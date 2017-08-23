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

    <!-- Theme CSS & JavaScript -->
    <script src="{{asset("js/creative.min.js")}}"></script>
    <link href="{{asset("css/creative.css")}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{asset("vendor/font-awesome/css/font-awesome.min.css")}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">

    <!-- jQuery Autocomplete -->
    <script>
        $( function() {
            var availableLocations = [ "University of Bath", "Bath Spa University", "University of Bristol" ];

            $( "#locationselect" ).autocomplete({
                source: availableLocations
            });
        } );
    </script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
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
                    <li>
                        <a class="page-scroll nav-btn" href="{{ route('login') }}">Log In</a>
                    </li>
                    <li>
                        <a class="page-scroll nav-btn nav-btn-muted" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <li>
                        <a class="page-scroll nav-btn" href="{{ route('profile') }}">Welcome back, {{ Auth::user()->first_name }}. </a>
                    </li>
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
</html>