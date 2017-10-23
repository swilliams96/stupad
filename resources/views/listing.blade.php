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
                        <a href="#" class="btn btn-white btn-xl" id="share-button" tabindex="0" role="button" data-html="true" data-trigger="focus" data-placement="bottom" data-toggle="popover" data-content="{{ $sharebuttonshtml }}">
                            Share<i class="fa fa-share-alt fa-pad-5l"></i>
                        </a>
                        @if ($saved)
                        <a href="#" class="btn btn-white btn-xl" id="unsave-listing-button">Unsave<i class="fa fa-heart fa-pad-5l"></i></a>
                        @else
                        <a href="#" class="btn btn-white btn-xl" id="save-listing-button">Save<i class="fa fa-heart fa-pad-5l"></i></a>
                        @endif
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

                    <div class="listing-page-gallery">
                        <h2>Gallery</h2>
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

    <script>
        $(function () {
            $('[data-toggle="popover"]').popover();
        })

        $()
    </script>

    @include('scripts.savebuttons')

    <script>
        $('#share-button').on('click', function() {
            return false;
        })
    </script>

    @include('common.footer')

@endsection

@push('seotags')
    <meta property="og:title" content="{{ $listing->title }}"/>
    <meta property="og:description" content="{{ $listing->short_description }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="www.stupad.co.uk/listings/{{ $listing->id }}"/>
    <meta property="og:image" content="{{ $listing->header->file() }}"/>
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $listing->title }}">
    <meta name="twitter:description" content="{{ $listing->short_description }}">
    <meta name="twitter:image" content="{{ $listing->header->file() }}">
    <meta name="twitter:domain" content="StuPad.co.uk">
@endpush