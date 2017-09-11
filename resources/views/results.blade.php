@extends('layouts.app')

@section('content')

    <section id="results">
        <div class="container">
            <div class="row">
                <!-- FILTERS -->
                <div class="col-lg-3">
                    <form id="filters" class="filters" method="POST" action="{{ route('results') }}">
                        <h2>Refine your search...</h2>
                        <hr class="filters-hr">

                        {{ csrf_field() }}

                        <input type="hidden" name="location" value="{{ $location_name }}">

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

                        <button class="btn btn-primary btn-search" type="submit">Search</button>

                    </form>
                </div>

                <!-- MAIN CONTENT -->
                <div class="content col-lg-9" >
                @if (count($listings) == 0)
                    <h2 class="section-heading">No results found for {{ $location_name }}...</h2>
                    <div class="listing no-results">
                        <p>Unfortunately we couldn't find any results matching your search criteria in the {{ $location_name }} area.</p>
                        <p>Why not try searching again for something slightly different, or try looking <a href="{{ route('search') }}">somewhere else</a> instead.</p>
                    </div>
                @else
                    <h2 class="section-heading">Properties in {{ $location_name }}... <span class="count">({{ count($listings) }})</span></h2>

                @foreach ($listings as $listing)
                    <div class="listing">
                        <div class="listing-image col-lg-4">
                            <!-- TODO: add listing url to the listings table and add this to all links -->
                            <a href="#">
                                <img src="./listing-images/{{ $listing->id }}/755c8dc7-4a87-4112-a8f9-73f3681d08b9.jpg" />
                            </a>
                        </div>

                        <div class="listing-details col-lg-8">
                            <h3><a href="#">{{ $listing->title }}</a></h3>

                            <span class="rent-amount">£{{ $listing->rent_period == 'week' ? ($listing->rent_value . 'pw') : (round($listing->rent_value * 52 / 12) . 'pcm') }}</span>

                            <div class="description">
                                {{ $listing->short_description }} ...
                            </div>

                            <div class="listing-footer">
                                <a href="#">More details...</a>
                            </div>

                            <ul class="listing-icons">
                                <li title="{{ $listing->bedrooms == 0 ? 'Studio (1 bedroom)' : ($listing->bedrooms . ($listing->bedrooms > 1 ? ' bedrooms' : ' bedroom')) }}"><i class="fa fa-bed fa-pad-5"></i>{{ $listing->bedrooms == 0 ? 'Studio' : $listing->bedrooms }}</li>
                                <li title="{{ $listing->bathrooms . ($listing->bathrooms > 1 ? ' bathrooms' : ' bathroom') }}"><i class="fa fa-bath fa-pad-5"></i>{{ $listing->bathrooms }}</li>
                                <li title="{{ $listing->town_distance . ($listing->town_distance > 1 ? ' min' : ' mins') . ' from centre' }}"><i class="fa fa-map-marker fa-pad-5"></i>{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') }}</li>
                                @if ($listing->furnished == true)<li title="Furnished"><i class="fa fa-check fa-pad-5"></i>Furnished</li>@endif
                                @if ($listing->bills_included == true)<li title="Bills included"><i class="fa fa-envelope-open fa-pad-5"></i>Bills Included</li>@endif
                                @if ($listing->pets_allowed == true)<li title="Pets allowed"><i class="fa fa-paw fa-pad-5"></i>Allowed</li>@endif
                            </ul>
                        </div>
                    </div>

                @endforeach
                @endif
                </div>
            </div>
        </div>
    </section>

    @include('common.footer')

    <!-- FILTERS PAGE SCROLLING -->
    <script>
        var filters_base_scroll = 0;	// initialise
        var max_margin_top = 0;			// initialise
        $(document).ready(function() {
            filters_base_scroll = parseInt($('#filters').css('margin-top'), 10);            // calculate
            max_margin_top = $("#footer").offset().top - $("#filters").height() - 320;      // calculate
            setTimeout(function(){ $(window).scroll() }, 500);
           ;
        });
        $(window).scroll(function() {
            if (window.matchMedia('(min-width: 1200px)').matches && $(window).width() >= 1200) {
                $("#filters").css("margin-top", Math.min(filters_base_scroll + $(window).scrollTop(), max_margin_top) + "px");
            }
        });
    </script>

    <!-- SLIDERS -->
    <script>
        // TODO: SET DEFAULTS TO THE SESSION DATA WHERE SESSION DATA IS SET
        $(function() {
            var rent_defaults = [
                {{ Cookie::get('lastsearch_rent_min', 100) }},
                {{ Cookie::get('lastsearch_rent_max', 250) }}
            ];
            var rent_max = 500;
            var currency = "£";
            $("#rent-min-label").html(currency + rent_defaults[0]);
            $("#rent-max-label").html(currency + rent_defaults[1]);
            $("#rent-slider").slider({
                min: 5,
                max: rent_max,
                step: 5,
                range: true,
                values: rent_defaults,
                slide: function(event, ui) {
                    $("#rent-min-label").html(currency + ui.values[0]);
                    $("#rent-max-label").html(currency + ui.values[1]);
                    $("#rent-min").val(ui.values[0]);
                    $("#rent-max").val(ui.values[1]);

                    if (ui.values[0] == rent_max) {
                        $("#rent-min-label").html(currency + rent_max + "+");
                    }
                    if (ui.values[1] == rent_max) {
                        $("#rent-max-label").html(currency + rent_max + "+");
                    }

                    if (ui.values[0] == ui.values[1]) {
                        if (ui.values[1] == 5) {
                            $("#rent-min-label").html("Any");
                            $("#rent-max-label").html("");
                        } else if (ui.values[0] == rent_max) {
                            $("#rent-min-label").html("");
                            $("#rent-max-label").html(currency + rent_max + "+");
                        } else {
                            $("#rent-min-label").html(currency + ui.values[0]);
                            $("#rent-max-label").html("");
                        }
                    } else if (ui.values[0] == 5 && ui.values[1] == rent_max) {
                        $("#rent-min-label").html("Any");
                        $("#rent-max-label").html("");
                    }
                }
            });

            var options_bedrooms = ["Studio", "1 bedroom", "2 bedrooms", "3 bedrooms", "4 bedrooms", "5+ bedrooms"];
            var bedroom_defaults = [
                {{ Cookie::get('lastsearch_bedrooms_min', 2) }},
                {{ Cookie::get('lastsearch_bedrooms_max', 4) }}
            ];
            $("#bedrooms-min-label").html(options_bedrooms[bedroom_defaults[0]]);
            $("#bedrooms-max-label").html(options_bedrooms[bedroom_defaults[1]]);
            $("#bedrooms-slider").slider({
                min: 0,
                max: 5,
                range: true,
                values: bedroom_defaults,
                slide: function(event, ui) {
                    $("#bedrooms-min-label").html(options_bedrooms[ui.values[0]]);
                    $("#bedrooms-max-label").html(options_bedrooms[ui.values[1]]);
                    $("#bedrooms-min").val(ui.values[0]);
                    $("#bedrooms-max").val(ui.values[1]);

                    if (ui.values[0] == ui.values[1]) {
                        if (ui.values[1] == 0) {
                            $("#bedrooms-min-label").html(options_bedrooms[0]);
                            $("#bedrooms-max-label").html("");
                        } else if (ui.values[0] == 5) {
                            $("#bedrooms-min-label").html("");
                            $("#bedrooms-max-label").html(options_bedrooms[5]);
                        } else {
                            $("#bedrooms-min-label").html(options_bedrooms[ui.values[0]]);
                            $("#bedrooms-max-label").html("");
                        }
                    } else if (ui.values[0] == 0 && ui.values[1] == 5) {
                        $("#bedrooms-min-label").html("Any");
                        $("#bedrooms-max-label").html("");
                    }
                }
            });

            var options_bathrooms = ["1 bathroom", "2 bathrooms", "3 bathrooms", "4 bathrooms", "5+ bathrooms"];
            var bathroom_defaults = [
                {{ Cookie::get('lastsearch_bathrooms_min', 1) }},
                {{ Cookie::get('lastsearch_bathrooms_max', 4) }}
            ];
            $("#bathrooms-min-label").html(options_bathrooms[bathroom_defaults[0]-1]);
            $("#bathrooms-max-label").html(options_bathrooms[bathroom_defaults[1]-1]);
            $("#bathrooms-slider").slider({
                min: 1,
                max: 5,
                range: true,
                values: bathroom_defaults,
                slide: function(event, ui) {
                    $("#bathrooms-min-label").html(options_bathrooms[ui.values[0]-1]);
                    $("#bathrooms-max-label").html(options_bathrooms[ui.values[1]-1]);
                    $("#bathrooms-min").val(ui.values[0]);
                    $("#bathrooms-max").val(ui.values[1]);

                    if (ui.values[0] == ui.values[1]) {
                        if (ui.values[1] == 1) {
                            $("#bathrooms-min-label").html(options_bathrooms[1-1]);
                            $("#bathrooms-max-label").html("");
                        } else if (ui.values[0] == 5) {
                            $("#bathrooms-min-label").html("");
                            $("#bathrooms-max-label").html(options_bathrooms[5-1]);
                        } else {
                            $("#bathrooms-min-label").html(options_bathrooms[ui.values[0]-1]);
                            $("#bathrooms-max-label").html("");
                        }
                    } else if (ui.values[0] == 1 && ui.values[1] == 5) {
                        $("#bathrooms-min-label").html("Any");
                        $("#bathrooms-max-label").html("");
                    }
                }
            });

            var distance_default = {{ Cookie::get('lastsearch_distance', 45) }};
            var distance_max = 60;
            var measurement = " mins";
            $("#distance-label").html("<" + distance_default + measurement);
            $("#distance-slider").slider({
                min: 10,
                max: distance_max,
                step: 5,
                range: "min",
                value: distance_default,
                slide: function(event, ui) {
                    $("#distance-label").html("<" + ui.value + measurement);
                    $("#distance").val(ui.value);

                    if (ui.value == distance_max) {
                        $("#distance-label").html("Any distance");
                    }
                }
            });

            // SET DEFAULTS FOR HIDDEN INPUT FIELDS
            $("#rent-min").val(rent_defaults[0]);
            $("#rent-max").val(rent_defaults[1]);
            $("#bedrooms-min").val(bedroom_defaults[0]);
            $("#bedrooms-max").val(bedroom_defaults[1]);
            $("#bathrooms-min").val(bathroom_defaults[0]);
            $("#bathrooms-max").val(bathroom_defaults[1]);
            $("#distance").val(distance_default);

        });
    </script>

    @include('common.autocomplete')

@endsection