@extends('layouts.app')

@section('content')

    <section id="listing-page-header">
        <div class="bg-img" style="background-image: url('{{ $listing->header->file() }}');"></div>
        <h1>{{ $listing->title }}</h1>
        <h2>£{{ $listing->rent_period == 'week' ? (round($listing->rent_value) . 'pw') : (round($listing->rent_value * 52 / 12) . 'pcm') }}</h2>
    </section>

    <section id="listing-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-12">
                    <a class="listing-back-link" href="{{ Cookie::has('lastsearch_location') ? (route('results') . '/' . App\University::where('name', Cookie::get('lastsearch_location'))->first()->slug) : route('search') }}"><i class="fa fa-chevron-left fa-pad-5"></i>Back to search ...</a>

                    <div class="listing-page-buttons">
                        <!-- Below save button will need to be a hidden form with a POST link to a Favourites Controller in Laravel --></share-button>
                        <a href="javascript: return false;" class="btn btn-save-listing btn-xl">Share<i class="fa fa-share-alt fa-pad-5l"></i></a>
                        <a href="javascript:;" class="btn btn-save-listing btn-xl">Save<i class="fa fa-heart fa-pad-5l"></i></a>
                        <a href="#" class="btn btn-primary btn-xl">Contact<i class="fa fa-user-circle fa-pad-5l"></i></a>
                    </div>

                    <div class="listing-page-description">
                        <h2>Description</h2>
                        @foreach ($description as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                    </div>

                    <h2>Information</h2>
                    <ul class="listing-page-info">
                        <li><i class="fa fa-bed"></i>{{ $listing->bedrooms == 0 ? 'Studio (1 bedroom)' : ($listing->bedrooms . ($listing->bedrooms > 1 ? ' bedrooms' : ' bedroom')) }}</li>
                        <li><i class="fa fa-bath"></i>{{ $listing->bathrooms . ($listing->bathrooms > 1 ? ' bathrooms' : ' bathroom') }}</li>
                        @if ($listing->town_distance != null)<li><i class="fa fa-map-marker"></i>{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') . ' from ' . $listing->area->name . ' ' . $listing->area->suffix . ' centre' }}</li>@endif
                        @if ($listing->furnished == true)<li><i class="fa fa-check"></i>Furnished</li>@endif
                        @if ($listing->bills_included == true)<li><i class="fa fa-envelope-open"></i>Bills included</li>@endif
                        @if ($listing->pets_allowed == true)<li><i class="fa fa-paw"></i>Pets allowed</li>@endif
                    </ul>

                    <!--
                    <ul class="listing-icons">
                        <li title="{{ $listing->bedrooms == 0 ? 'Studio (1 bedroom)' : ($listing->bedrooms . ($listing->bedrooms > 1 ? ' bedrooms' : ' bedroom')) }}"><i class="fa fa-bed fa-pad-5"></i>{{ $listing->bedrooms == 0 ? 'Studio' : $listing->bedrooms }}</li>
                        <li title="{{ $listing->bathrooms . ($listing->bathrooms > 1 ? ' bathrooms' : ' bathroom') }}"><i class="fa fa-bath fa-pad-5"></i>{{ $listing->bathrooms }}</li>
                        @if ($listing->town_distance != null)<li title="{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') . ' from ' . $listing->area->name . ' centre' }}"><i class="fa fa-map-marker fa-pad-5"></i>{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') }}</li>@endif
                        @if ($listing->furnished == true)<li title="Furnished"><i class="fa fa-check fa-pad-5"></i>Furnished</li>@endif
                        @if ($listing->bills_included == true)<li title="Bills included"><i class="fa fa-envelope-open fa-pad-5"></i>Bills Included</li>@endif
                        @if ($listing->pets_allowed == true)<li title="Pets allowed"><i class="fa fa-paw fa-pad-5"></i>Allowed</li>@endif
                    </ul>
                    -->

                    <div class="listing-page-gallery">
                        <h2>Gallery</h2>
                        <!-- TODO: LISTING IMAGES FROM LISTING IMAGE TABLE -->
                        <a href="{{ $listing->header->file() }}" data-caption="" data-fancybox="gallery" data-type="image" draggable=false >
                            <img src="{{ $listing->header->file() }}" draggable=false />
                        </a>
                        <div class="additional-images">
                        @foreach ($listing->images as $image)
                            @if ($image != $listing->header)
                            <a href="{{ $image->file() }}" data-caption="" data-fancybox="gallery" data-type="image" draggable=false >
                                <img src="{{ $image->file() }}" draggable=false />
                            </a>
                            @endif
                        @endforeach
                        </div>
                    </div>

                    <div class="listing-page-map">
                        <h2>Location</h2>
                        <div id="map"></div>
                        <script>
                            function initMap() {
                                var loc = { lat: {{ $listing->lat }}, lng: {{ $listing->lng }} };
                                var map = new google.maps.Map(document.getElementById('map'), {
                                    zoom: 14,
                                    center: loc,
                                    backgroundColor: "#ccc",
                                    minZoom: 9,
                                    maxZoom: 18,
                                    clickableIcons: false,
                                    disableDefaultUI: false,
                                    zoomControl: true,
                                    streetViewControl: false,
                                    mapTypeControl: false,
                                    fullscreenControl: false,
                                    scrollwheel: false,
                                    gestureHandling: 'cooperative',
                                    signInControl: false,
                                    styles: [
                                        {
                                            "featureType": "administrative.land_parcel",
                                            "elementType": "labels",
                                            "stylers": [
                                                {
                                                    "visibility": "off"
                                                }
                                            ]
                                        },
                                        {
                                            "featureType": "road.local",
                                            "elementType": "labels",
                                            "stylers": [
                                                {
                                                    "visibility": "off"
                                                }
                                            ]
                                        }
                                    ]
                                });
                                var marker = new google.maps.Marker({
                                    position: loc,
                                    map: map
                                });

                                google.maps.event.addListener(map, 'mousedown', function(event) {
                                    map.setOptions( { scrollwheel: true } );
                                });
                                google.maps.event.addListener(map, 'mouseout', function(event){
                                    map.setOptions( { scrollwheel: false } );
                                });

                                if (!window.matchMedia( "(min-width: 767px)" ).matches) {	// assume mobile
                                    console.log("Mobile device detected - hiding map zoom controls.");
                                    map.setOptions( { zoomControl: false } );
                                }
                            }
                        </script>
                        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAekgyp9lcORQE5azKeeqEd7UD0_tZGE-k&callback=initMap"></script>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Fancybox -->
    <script type="text/javascript">
        $("[data-fancybox]").fancybox({
            protect : true,
            speed : 300,
            loop : false,
            thumbs : {
                showOnStart : true
            },
            slideShow  : false
        });
    </script>

    @include('common.footer')

@endsection