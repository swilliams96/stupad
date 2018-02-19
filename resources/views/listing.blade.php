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
                        <a href="#" class="btn btn-primary btn-xl" id="contact-button">Contact<i class="fa fa-user-circle fa-pad-5l"></i></a>
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

    @if (Auth::check())
    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Get in Touch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <i class="fa fa-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="contact-details">
                        <pre>Loading details...</pre>
                        <p>If the details do not load, please send a message to the listing owner instead:</p>
                    </div>
                    <label>Message:</label>
                    <textarea id="contact-msg-input" placeholder="Send a message..."></textarea>
                    <button class="btn btn-sm btn-grey" id="send-message-btn">Send</button>
                </div>
            </div>
        </div>
    </div>
    @endif

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

    <!-- Share buttons -->
    <script>
        $('#share-button').on('click', function() {
            return false;
        });

        $(document.body).on('click', '#share-button-messenger', function() {
            FB.ui({
                method: 'send',
                link: 'https://www.stupad.co.uk/listings/{{ $listing->id }}',
            });
            return false;
        });

        $(document.body).on('click', '#share-button-facebook', function() {
            FB.ui({
                method: 'share',
                mobile_iframe: true,
                quote: 'Check out this student house I found on StuPad!',
                href: 'https://www.stupad.co.uk/listings/{{ $listing->id }}',
            });
            return false;
        });
    </script>

    <script>
        $('#contact-button').on('click', function() {
            FB.AppEvents.logEvent('CONTACT_BUTTON_PRESSED');
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/listings/contact-details/{{ $listing->id }}',
                type: 'POST',
                data: {_token: CSRF_TOKEN},
                dataType: 'JSON',
                success: function (response) {
                    console.log(response.message.toString());
                    if (response.status == 200) {               // CONTACT DETAILS RELEASED
                        FB.AppEvents.logEvent('CONTACT_DETAILS_VIEWED');
                        $('#contact-details').html('');
                        if (response.data.phone) $('#contact-details').append('<label>Phone:</label><pre>' + response.data.phone + '</pre>');
                        if (response.data.email) $('#contact-details').append('<label>Email:</label><pre>' + response.data.email + '</pre>');
                        if (response.data.phone || response.data.email) $('#contact-details').append('<p style="padding-top: 24px">Alternatively, you can send a message to the listing owner below:</p>');
                        else $('#contact-details').append('<p><i>An error occurred. No contact details could be found for this listing. Please contact the listing owner by sending a message below instead:</i></p>');

                    } else if (response.status == 202) {        // APPROVAL REQUIRED
                        $('#contact-details').html('').append('<p style="margin-bottom: 0;">Approval is required to view the contact details for this listing. Please press the button below to request them from the listing owner.</p>')
                            .append('<button class="btn btn-primary" id="request-contact-details-btn" style="margin: 16px auto">Request Contact Details</button>')
                            .append('<p>If you just have a question about the listing, or want to get in touch straight away, you can send a message to the listing owner below:</p>');

                    } else if (response.status == 403) {        // MESSAGES ONLY
                        $('#contact-details').html('').append('<p>Contact details are private for this listing. To contact the listing owner, please send them a message below:</p>');

                    } else if (response.status == 402) {        // NOT LOGGED IN
                        window.location = '{{ route('login') }}';

                    }  else if (response.status == 404) {        // COULDN'T FIND LISTING
                        console.error('This listing could not be found... Refreshing the page...');
                        setTimeout(function() { location.reload(true); }, 1000);

                    } else {
                        console.error('An error occurred while retrieving contact details for this listing.');
                        console.error('Error ' + response.status);
                        console.error(response.responseText);
                    }
                },
                error: function(e) {
                    console.error('An error occurred while retrieving contact details for this listing.');
                    console.log('Refreshing page...');
                    setTimeout(function() { location.reload(true); }, 1000);
                }
            });
            $('#contactModal').modal();
        });

        $('#contactModal').on('shown.bs.modal', function() {
            $('#contact-msg-input').focus();
        })

        $('body').on('click', '#request-contact-details-btn', function() {
            // AJAX REQUEST CONTACT DETAILS
        });
    </script>

    @include('scripts.popovers')

    @include('scripts.savebuttons')

    @include('common.footer')

@endsection

@push('seotags')
    <meta property="og:title" content="{{ $listing->title }}"/>
    <meta property="og:description" content="{{ $listing->short_description }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="https://www.stupad.co.uk/listings/{{ $listing->id }}"/>
    <meta property="og:image" content="{{ $listing->header->file() }}"/>
    <meta property="og:image:width" content="{{ getimagesize($listing->header->file())[0] }}"/>
    <meta property="og:image:height" content="{{ getimagesize($listing->header->file())[1] }}"/>
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $listing->title }}">
    <meta name="twitter:description" content="{{ $listing->short_description }}">
    <meta name="twitter:image" content="{{ $listing->header->file() }}">
@endpush