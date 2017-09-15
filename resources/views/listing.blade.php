@extends('layouts.app')

@section('content')

    <section id="listing-page-header">
        <div class="bg-img" style="background-image: url('/listing-images/{{ $listing->id }}/755c8dc7-4a87-4112-a8f9-73f3681d08b9.jpg');"></div>
        <h1>{{ $listing->title }}</h1>
        <h2>£{{ $listing->rent_period == 'week' ? ($listing->rent_value . 'pw') : (round($listing->rent_value * 52 / 12) . 'pcm') }}</h2>
    </section>

    <section id="listing-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-12">
                    <a class="listing-back-link" href="{{ Cookie::has('lastsearch_location') ? (route('results') . '/' . App\University::where('name', Cookie::get('lastsearch_location'))->first()->slug) : route('search') }}"><i class="fa fa-chevron-left fa-pad-5"></i>Back to search ...</a>

                    <!-- TODO : rewrite this using bootstrap to automatically stack vertically on mobile -->
                    <div class="listing-page-buttons">
                        <!-- Below save button will need to be a hidden form with a POST link to a Favourites Controller in Laravel --></share-button>
                        <a href="javascript:;" class="btn btn-save-listing btn-xl">Share<i class="fa fa-share-alt fa-pad-5l"></i></a>
                        <a href="javascript:;" class="btn btn-save-listing btn-xl">Save<i class="fa fa-heart fa-pad-5l"></i></a>
                        <a href="#" class="btn btn-primary btn-xl">Contact<i class="fa fa-user-circle fa-pad-5l"></i></a>
                    </div>



                    <div class="listing-page-description">
                        <h2>Description</h2>
                        <p>{{ $listing->description }}</p>
                        <ul class="listing-icons">
                            <li title="{{ $listing->bedrooms == 0 ? 'Studio (1 bedroom)' : ($listing->bedrooms . ($listing->bedrooms > 1 ? ' bedrooms' : ' bedroom')) }}"><i class="fa fa-bed fa-pad-5"></i>{{ $listing->bedrooms == 0 ? 'Studio' : $listing->bedrooms }}</li>
                            <li title="{{ $listing->bathrooms . ($listing->bathrooms > 1 ? ' bathrooms' : ' bathroom') }}"><i class="fa fa-bath fa-pad-5"></i>{{ $listing->bathrooms }}</li>
                            <li title="{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') . ' from ' . $listing->area->name . ' centre' }}"><i class="fa fa-map-marker fa-pad-5"></i>{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') }}</li>
                            @if ($listing->furnished == true)<li title="Furnished"><i class="fa fa-check fa-pad-5"></i>Furnished</li>@endif
                            @if ($listing->bills_included == true)<li title="Bills included"><i class="fa fa-envelope-open fa-pad-5"></i>Bills Included</li>@endif
                            @if ($listing->pets_allowed == true)<li title="Pets allowed"><i class="fa fa-paw fa-pad-5"></i>Allowed</li>@endif
                        </ul>
                    </div>

                    <div class="listing-page-gallery">
                        <h2>Gallery</h2>
                        <!-- TODO: LISTING IMAGES FROM LISTING IMAGE TABLE -->
                        <a href="/listing-images/{{ $listing->id }}/755c8dc7-4a87-4112-a8f9-73f3681d08b9.jpg" data-caption="" data-fancybox="gallery" data-type="image"draggable=false >
                            <img src="/listing-images/{{ $listing->id }}/755c8dc7-4a87-4112-a8f9-73f3681d08b9.jpg" draggable=false />
                        </a>
                        <div class="additional-images">
                            <a href="/listing-images/{{ $listing->id }}/343f125f-55b5-47b7-be26-448c27a0016b.jpg" data-caption="" data-fancybox="gallery" data-type="image" draggable=false >
                                <img src="/listing-images/{{ $listing->id }}/343f125f-55b5-47b7-be26-448c27a0016b.jpg" draggable=false />
                            </a>
                            <a href="/listing-images/{{ $listing->id }}/491d0089-8e44-4f49-8487-553a3ea3be82.jpg" data-caption="" data-fancybox="gallery" data-type="image" draggable=false >
                                <img src="/listing-images/{{ $listing->id }}/491d0089-8e44-4f49-8487-553a3ea3be82.jpg" draggable=false />
                            </a>
                            <a href="/listing-images/{{ $listing->id }}/ab9176cd-d13b-46e8-ae7a-362b23ee81b6.jpg" data-caption="" data-fancybox="gallery" data-type="image" draggable=false >
                                <img src="/listing-images/{{ $listing->id }}/ab9176cd-d13b-46e8-ae7a-362b23ee81b6.jpg" draggable=false />
                            </a>
                            <a href="/listing-images/{{ $listing->id }}/ba621748-4004-42de-95e9-f78cba0688fc.jpg" data-caption="" data-fancybox="gallery" data-type="image" draggable=false >
                                <img src="/listing-images/{{ $listing->id }}/ba621748-4004-42de-95e9-f78cba0688fc.jpg" draggable=false />
                            </a>
                            <a href="/listing-images/{{ $listing->id }}/a221cd03-b515-4fbb-a5e7-12e35c284286.jpg" data-caption="" data-fancybox="gallery" data-type="image" draggable=false >
                                <img src="/listing-images/{{ $listing->id }}/a221cd03-b515-4fbb-a5e7-12e35c284286.jpg" draggable=false />
                            </a>
                            <a href="/listing-images/{{ $listing->id }}/d02aad24-be9a-4c7b-9867-c7d009570e5d.jpg" data-caption="" data-fancybox="gallery" data-type="image" draggable=false >
                                <img src="/listing-images/{{ $listing->id }}/d02aad24-be9a-4c7b-9867-c7d009570e5d.jpg" draggable=false />
                            </a>
                            <a href="/listing-images/{{ $listing->id }}/c765e865-7f88-4853-a000-95bebbb083fd.jpg" data-caption="" data-fancybox="gallery" data-type="image" draggable=false >
                                <img src="/listing-images/{{ $listing->id }}/c765e865-7f88-4853-a000-95bebbb083fd.jpg" draggable=false />
                            </a>
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
                                    map.setOptions( { fullscreenControl: true } );
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