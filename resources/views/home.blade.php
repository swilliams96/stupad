<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A student-centric house search website that makes finding your student house easy.">
    <meta name="author" content="Sam Williams">

    <title>StuPad - Student Housing Search</title>

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
            <a class="navbar-brand page-scroll" href="/"><span class="stu">STU</span><span class="pad">PAD</span></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a class="page-scroll nav-btn" href="./login">Log In</a>
                </li>
                <li>
                    <a class="page-scroll nav-btn nav-btn-muted" href="./register">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header>
    <div class="header-content">
        <div class="header-content-inner">
            <h1 id="homeHeading">Easy Accommodation for Students</h1>
            <hr>
            <form action="./results">
                {{ csrf_field() }}
                <input type="text" id="locationselect" name="location" placeholder="What university are you at?"/>
                <button class="btn btn-primary btn-xl" type="submit">Search</button>
            </form>

        </div>
    </div>
</header>

<section id="services">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">Find Your Next Home</h2>
                <hr class="primary">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-search text-primary sr-icons"></i>
                    <h3>Smart Searching</h3>
                    <p class="text-muted">Search criteria made with students in mind.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-newspaper-o text-primary sr-icons"></i>
                    <h3>Flexible Filters</h3>
                    <p class="text-muted">Easily filter your search to help you find the perfect house.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-gbp text-primary sr-icons"></i>
                    <h3>All For Free!</h3>
                    <p class="text-muted">Discover your home for next year without paying a penny!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">About Us</h2></h2>
                <hr class="primary">
            </div>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-lg-12 text-center">
                <p>
                    As a student it was always hard to find the perfect house to live in. Like every uni student I didn't have time to search
                    through hundreds of different websites and listings, so I created <span class="stu">STU</span><span class="pad">PAD</span>
                    to help make it easier for busy students to find accommodation.
                </p>
            </div>
        </div>
    </div>
</section>

<section id="landlord-container">
    <a href="./landlord" class="area-link">
        <section id="landlord">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Got a Property to Rent?</h2>
                        <hr class="primary">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 text-center">
                        <i class="fa fa-5x fa-home text-primary sr-icons icon-fade"></i>
                        <i class="fa fa-5x fa-home text-primary sr-icons"></i>
                        <i class="fa fa-5x fa-home text-primary sr-icons icon-fade"></i>
                        <p class="text-muted">
                            Click <span class="subtle-link">here</span> to visit our Landlord Portal and find out more about how
                            easy it is to get your property in front of students looking to rent.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </a>
</section>

<section class="bg-dark" id="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="service-box">
                    <h3 class="section-heading align-left">Contact Us</h3>
                    <p><i class="fa fa-home sr-icons"></i>70 University St, London, AB1 2BC</p>
                    <p><i class="fa fa-phone sr-icons"></i>(+44) 1234 567898</p>
                    <p><i class="fa fa-envelope sr-icons"></i>swilliams96@outlook.com</p>
                </div>
            </div>
        </div>
    </div>
</section>
</body>

</html>