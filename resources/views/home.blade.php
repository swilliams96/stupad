@extends('layouts.app')

@section('content')
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

    @include('common.footer')

@endsection
