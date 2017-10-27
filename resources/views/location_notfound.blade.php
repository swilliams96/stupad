@extends('layouts.app')

@section('content')

    <section id="notfound">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Location Not Found</h2></h2>
                    <!--<hr class="primary">-->
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-lg-10 col-lg-offset-1 text-center">
                    <p>
                        <i>Could not find a valid location matching '<span style="font-weight: 100;">{{ $search }}</span>'.</i>
                    </p>
                    @if (count($suggestions) > 0)
                        <p>Did you mean any of the following?</p>
                        @foreach ($suggestions as $suggestion)
                            <a href="results/{{ $suggestion->slug }}">{{ $suggestion->name }}</a><br/>
                        @endforeach
                    @endif
                    <p class="summary-text" style="margin-top: 40px;">
                        Unfortunately <span class="stu">STU</span><span class="pad2">PAD</span> only supports a small number of locations at present.
                        We hope to soon expand to other universities and towns across the UK.
                @if (Auth::check())
                        If you would like to be notified when we begin supporting this location and other locations in future, just click the button below:
                    </p>
                    <form action="/subscribe/locations" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" id="email" name="email" value="{{ Auth::user()->email }}"/>
                        <button class="btn" style="width: auto;">Subscribe to Location</button>
                    </form>
                    <p class="summary-text small-text">
                        You can manage your email subscriptions in <a href="{{ route('profile') }}">settings</a>.
                    </p>
                @else
                        If you would like to be notified when we begin supporting this location and other locations in future, just fill out your email address below:
                    </p>
                    <form action="/subscribe/locations" method="POST">
                        {{ csrf_field() }}
                        <input type="text" id="email" name="email" placeholder="Email Address"/>
                        <button class="btn" type="submit">Submit</button>
                    </form>
                    <p class="summary-text small-text">
                        We won't ever spam you, sell your email address or use it for any purpose other than to let you know when
                        we launch functionality for this location. If you ever want to be removed from this please get in touch.
                    </p>
                @endif
                    <p style="padding-top: 20px;">In the meantime, why not try searching somewhere else...</p>
                </div>
            </div>
        </div>
    </section>

    <section id="search" style="background-image: none; background-color: transparent; padding: 0 0 80px; min-height: auto;">
        <div class="container">
            <div class="row">
                <!-- FILTERS -->
                <div class="col-lg-12">
                    <form class="filters" method="POST" action="{{ route('results') }}">
                        <h2>Search</h2>
                        <hr class="filters-hr">

                        {{ csrf_field() }}

                        <input type="text" id="location" name="location" placeholder="Where are you searching?" class='location-autocomplete'/>

                        <h3><i class="fa fa-gbp fa-pad-5 sr-icons"></i>Rent (per week)</h3>
                        <div id="rent-slider" class="slider"></div>
                        <div class="slider-labels">
                            <span id="rent-min-label" class="min-label"></span>
                            <span id="rent-max-label" class="max-label"></span>
                        </div>
                        <input type="hidden" id="rent-min" name="rent_min" value="0">
                        <input type="hidden" id="rent-max" name="rent_max" value="0">

                        <h3><i class="fa fa-bed fa-pad-5 sr-icons"></i>Bedrooms</h3>
                        <div id="bedrooms-slider" class="slider"></div>
                        <div class="slider-labels">
                            <span id="bedrooms-min-label" class="min-label"></span>
                            <span id="bedrooms-max-label" class="max-label"></span>
                        </div>
                        <input type="hidden" id="bedrooms-min" name="bedrooms_min" value="0">
                        <input type="hidden" id="bedrooms-max" name="bedrooms_max" value="0">

                        <h3><i class="fa fa-bath fa-1 fa-pad-5 sr-icons"></i>Bathrooms</h3>
                        <div id="bathrooms-slider" class="slider"></div>
                        <div class="slider-labels">
                            <span id="bathrooms-min-label" class="min-label"></span>
                            <span id="bathrooms-max-label" class="max-label"></span>
                        </div>
                        <input type="hidden" id="bathrooms-min" name="bathrooms_min" value="0">
                        <input type="hidden" id="bathrooms-max" name="bathrooms_max" value="0">

                        <h3 style="width: 100%;">
                            <span><i class="fa fa-map-marker fa-1 fa-pad-5 sr-icons"></i>Dist. from</span>
                            <select id="place" name="place">
                                <option value="campus"{{ Cookie::get('lastsearch_place') == 'campus' ? 'selected' : '' }}>Campus</option>
                                <option value="town"{{ Cookie::get('lastsearch_place') == 'town' ? 'selected' : '' }}>Town Centre</option>
                            </select>
                        </h3>
                        <div id="distance-slider" class="slider"></div>
                        <div class="slider-labels">
                            <span id="distance-label" class="min-label"></span>
                        </div>
                        <input type="hidden" id="distance" name="distance" value="0">

                        <button class="btn btn-primary btn-xl" type="submit">Search</button>

                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('scripts.autocomplete')

    @include('scripts.sliders')

@endsection