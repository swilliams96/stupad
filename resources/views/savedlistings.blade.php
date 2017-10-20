@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row dashboard">

        @include('common.dashboardsidebar', ['page' => 'savedlistings'])


        <!-- MAIN PAGE -->
            <div class="col-lg-7 mainpage no-float">
                <h1>Saved Listings</h1>
            @if (count($savedlistings) > 0)
                @foreach ($savedlistings as $listing)
                <div class="listing">
                    <div class="listing-image col-lg-3">
                        <a href="/listings/{{ $listing->id }}/{{ snake_case($listing->title, '-') }}">
                            <img src="{{ $listing->header->file() }}" />
                        </a>
                    </div>

                    <div class="listing-details col-lg-9">
                        <h3><a href="/listings/{{ $listing->id }}/{{ snake_case($listing->title, '-') }}" class="draggable">{{ $listing->title }}</a></h3>

                        <span class="rent-amount">£{{ $listing->rent_period == 'week' ? (round($listing->rent_value) . 'pw') : (round($listing->rent_value * 52 / 12) . 'pcm') }}</span>

                        <div class="description">
                            {{ $listing->short_description }}
                        </div>

                        <div class="listing-footer">
                            <a href="#" class="unsave-listing-button" listing="{{ $listing->id }}"><i class="fa fa-heart fa-pad-5"></i>Unsave listing</a>&middot;<!--
                         --><a href="/listings/{{ $listing->id }}/{{ snake_case($listing->title, '-') }}" class="draggable">More details...</a>
                        </div>

                        <ul class="listing-icons">
                            <li title="{{ $listing->bedrooms == 0 ? 'Studio (1 bedroom)' : ($listing->bedrooms . ($listing->bedrooms > 1 ? ' bedrooms' : ' bedroom')) }}"><i class="fa fa-bed fa-pad-5"></i>{{ $listing->bedrooms == 0 ? 'Studio' : $listing->bedrooms }}</li>
                            <li title="{{ $listing->bathrooms . ($listing->bathrooms > 1 ? ' bathrooms' : ' bathroom') }}"><i class="fa fa-bath fa-pad-5"></i>{{ $listing->bathrooms }}</li>
                            @if ($listing->town_distance != null)<li title="{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') . ' from ' . $listing->area->name . ' centre' }}"><i class="fa fa-map-marker fa-pad-5"></i>{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') }}</li>@endif
                            @if ($listing->furnished == true)<li title="Furnished"><i class="fa fa-check fa-pad-5"></i>Furnished</li>@endif
                            @if ($listing->bills_included == true)<li title="Bills included"><i class="fa fa-envelope-open fa-pad-5"></i>Bills Included</li>@endif
                            @if ($listing->pets_allowed == true)<li title="Pets allowed"><i class="fa fa-paw fa-pad-5"></i>Allowed</li>@endif
                        </ul>
                    </div>
                </div>
                @endforeach
            @else
                <div class="listing">
                    <p>You do not currently have any listings saved.</p>
                    <p>To save a listing, just <a href="{{ route('search') }}">search</a> for one you like, then click the button at the top of the page to save it here for later!</p>
                </div>
            @endif
            </div>


            <!-- PADDING COLUMN -->
            <div class="col-lg-2 no-float"></div>

        </div>
    </div>

    <script>
        // One-off unsaving functionality
        $('body').on('click', '.unsave-listing-button', function() {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/listings/' + $(this).attr('listing') + '/unsave',
                type: 'POST',
                data: {_token: CSRF_TOKEN},
                dataType: 'JSON',
                success: function (response) {
                    if (response.status == 200) {
                        console.log(response.message);
                    } else {
                        console.error('An error occurred while unsaving this listing.');
                        console.error('Error ' + response.status);
                        console.error(response.responseText);
                    }
                },
                error: function(e) {
                    console.error('An error occurred while unsaving this listing.');
                    console.log('Refreshing page...');
                    setTimeout(function() { location.reload(true); }, 1000);
                }
            });

            $(this).parents('.listing').css('display', 'none');

            let allhidden = true;
            $('.listing').each(function(i, listing) {
                if ($(this).css('display') != 'none') {
                    allhidden = false;
                }
            });
            if (allhidden) {
                location.reload(true);
            }

            return false;
        });
    </script>

@endsection