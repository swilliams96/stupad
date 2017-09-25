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
                                <option value="campus"{{ Cookie::get('lastsearch_place') == 'campus' ? ' selected' : '' }}>Campus</option>
                                <option value="town"{{ Cookie::get('lastsearch_place') == 'town' ? ' selected' : '' }}>Town Centre</option>
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
                    <h2 class="section-heading">No results found for {{ $area_name }}...</h2>
                    <div class="listing no-results">
                        <p>Unfortunately we couldn't find any results matching your search criteria in the {{ $area_name }} area.</p>
                        <p>Why not try searching again for something slightly different, or try looking <a href="{{ route('search') }}">somewhere else</a> instead.</p>
                    </div>
                @else
                    <h2 class="section-heading">Properties in {{ $area_name }}... <span class="count">({{ count($listings) }})</span></h2>

                @foreach ($listings as $listing)
                    <div class="listing">
                        <div class="listing-image col-lg-4">
                            <a href="/listing/{{ $listing->id }}/{{ snake_case($listing->title, '-') }}">
                                <img src="{{ $listing->header->file() }}" />
                            </a>
                        </div>

                        <div class="listing-details col-lg-8">
                            <h3><a href="/listing/{{ $listing->id }}/{{ snake_case($listing->title, '-') }}">{{ $listing->title }}</a></h3>

                            <span class="rent-amount">£{{ $listing->rent_period == 'week' ? (round($listing->rent_value) . 'pw') : (round($listing->rent_value * 52 / 12) . 'pcm') }}</span>

                            <div class="description">
                                {{ $listing->short_description }} ...
                            </div>

                            <div class="listing-footer">
                                <a href="/listing/{{ $listing->id }}/{{ snake_case($listing->title, '-') }}">More details...</a>
                            </div>

                            <ul class="listing-icons">
                                <li title="{{ $listing->bedrooms == 0 ? 'Studio (1 bedroom)' : ($listing->bedrooms . ($listing->bedrooms > 1 ? ' bedrooms' : ' bedroom')) }}"><i class="fa fa-bed fa-pad-5"></i>{{ $listing->bedrooms == 0 ? 'Studio' : $listing->bedrooms }}</li>
                                <li title="{{ $listing->bathrooms . ($listing->bathrooms > 1 ? ' bathrooms' : ' bathroom') }}"><i class="fa fa-bath fa-pad-5"></i>{{ $listing->bathrooms }}</li>
                                <li title="{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') . ' from ' . $listing->area->name . ' centre' }}"><i class="fa fa-map-marker fa-pad-5"></i>{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') }}</li>
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

    @include('scripts.autocomplete')

    @include('scripts.sliders')

@endsection