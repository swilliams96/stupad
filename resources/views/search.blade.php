@extends('layouts.app')

@section('content')

    <section id="search">
        <div class="container">
            <div class="row">
                <!-- FILTERS -->
                <div class="col-lg-12">
                    <form class="filters" method="POST" action="{{ route('results') }}">
                        <h2>Search</h2>
                        <hr class="filters-hr">

                        {{ csrf_field() }}

                        <input type="text" id="location" name="location" placeholder="Where are you searching?" class='location-autocomplete' value="{{ $location !== null ? $location : Cookie::get('lastsearch_location', '') }}"/>

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
                            <!--
                            <span style="padding-left: 4px;">by</span>
                            <select id="transport" name="transport">
                                <option value="any">Any method</option>
                                <option value="bus">Bus</option>
                                <option value="walk">Walking</option>
                                <option value="cycle">Cycling</option>
                            </select>
                            -->
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