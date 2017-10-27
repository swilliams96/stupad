@extends('layouts.app')

@section('content')

    <body id="page-top">

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-opaque">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="./index.html"><span class="stu">STU</span><span class="pad">PAD</span></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="page-scroll nav-btn" href="./login.html">Log In</a>
                    </li>
                    <li>
                        <a class="page-scroll nav-btn nav-btn-muted" href="./register.html">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="notfound">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Location Not Found</h2></h2>
                    <!--<hr class="primary">-->
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-lg-12 text-center">
                    <p>
                        <i>{{ $location }} is not currently supported.</i>
                    </p>
                    <p>
                        Did you mean: <a href="./search.html?location=uniofbath">University of Bath</a>
                    </p>
                    <p class="summary-text" style="margin-top: 70px;">
                        Unfortunately <span class="stu">STU</span><span class="pad2">PAD</span> only supports a small number of locations at present.
                        We hope to expand to other universities in towns across the UK shortly.
                        If you would like to be notified when we begin supporting this location just type your email address below:
                    </p>
                    <form action="" method="POST">
                        <input type="text" id="email" name="email" placeholder="Email Address"/>
                        <button class="btn" type="submit">Submit</button>
                    </form>
                    <p class="summary-text small-text">
                        We won't ever spam you, sell your email address or use it for any purpose other than to let you know when
                        we launch functionality for this location. If you ever want to be removed from this please get in touch.
                    </p>
                    <p class="small-text">In the meantime, why not try <a href="./search.html">searching somewhere else</a>.</p>
                </div>
            </div>
        </div>
    </section>

@endsection