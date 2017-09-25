@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row dashboard">

        @include('common.dashboardsidebar', ['page' => 'mylistings'])


        <!-- MAIN PAGE -->
            <div class="col-lg-6 mainpage no-float">
                <h1>My Listings</h1>
                <a href="{{ route('newlisting') }}" id="new-listing-btn"><i class="fa fa-plus fa-pad-5 sr-icons"></i>New Listing</a>

            @if (count($listings) > 0)
                @foreach ($listings as $listing)
                <div class="listing">
                    <div class="listing-image col-lg-3">
                        <a href="/listing/{{ $listing->id }}/{{ snake_case($listing->title, '-') }}">
                            <img src="{{ $listing->header->file() }}" />
                        </a>
                    </div>

                    <div class="listing-details col-lg-9">
                        <h3><a href="/listing/{{ $listing->id }}/{{ snake_case($listing->title, '-') }}">{{ $listing->title }}</a></h3>

                        <span class="rent-amount">£{{ $listing->rent_period == 'week' ? (round($listing->rent_value) . 'pw') : (round($listing->rent_value * 52 / 12) . 'pcm') }}</span>

                        <div class="description">
                            {{ $listing->short_description }} ...
                        </div>

                        <ul class="listing-icons">
                            <li title="{{ $listing->bedrooms == 0 ? 'Studio (1 bedroom)' : ($listing->bedrooms . ($listing->bedrooms > 1 ? ' bedrooms' : ' bedroom')) }}"><i class="fa fa-bed fa-pad-5"></i>{{ $listing->bedrooms == 0 ? 'Studio' : $listing->bedrooms }}</li>
                            <li title="{{ $listing->bathrooms . ($listing->bathrooms > 1 ? ' bathrooms' : ' bathroom') }}"><i class="fa fa-bath fa-pad-5"></i>{{ $listing->bathrooms }}</li>
                            @if ($listing->town_distance != null)<li title="{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') . ' from ' . $listing->area->name . ' centre' }}"><i class="fa fa-map-marker fa-pad-5"></i>{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') }}</li>@endif
                            @if ($listing->furnished == true)<li title="Furnished"><i class="fa fa-check fa-pad-5"></i>Furnished</li>@endif
                            @if ($listing->bills_included == true)<li title="Bills included"><i class="fa fa-envelope-open fa-pad-5"></i>Bills Included</li>@endif
                            @if ($listing->pets_allowed == true)<li title="Pets allowed"><i class="fa fa-paw fa-pad-5"></i>Allowed</li>@endif
                        </ul>

                        <div class="listing-footer">
                            Listing Options:
                            <a href="/listing/{{ $listing->id }}/{{ snake_case($listing->title, '-') }}"><i class="fa fa-eye fa-pad-5 sr-icons"></i>View</a>&middot;
                            <a href="/dashboard/listings/edit/{{ $listing->id }}"><i class="fa fa-pencil-square-o fa-pad-5 sr-icons"></i>Edit</a>&middot;
                            <a href="/dashboard/listings/delete/{{ $listing->id }}"><i class="fa fa-trash-o fa-pad-5 sr-icons"></i>Delete</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="listing">
                    <p>You do not currently have any listings. Get started by <a href="{{ route('newlisting') }}">creating your first one</a>!</p>
                </div>
            @endif

            </div>

            <!-- PADDING COLUMN -->
            <div class="col-lg-3 no-float"></div>

        </div>
    </div>

    @include('scripts.passwordvalidation')

@endsection