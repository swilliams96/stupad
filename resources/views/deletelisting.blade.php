@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row" style="margin: 0; height: 100%; display: table-row;">

        @include('common.dashboardsidebar', ['page' => 'deletelisting'])


        <!-- MAIN PAGE -->
            <div class="col-lg-5 mainpage no-float no-select">
                <h1>Delete Listing</h1>

                <div class="listing" style="margin: 0;">
                    <div class="listing-image col-lg-4">
                        <img src="{{ $listing->header->file() }}" />
                    </div>

                    <div class="listing-details col-lg-8">
                        <h3 style="color: #D64439;">{{ $listing->title }}</h3>

                        <span class="rent-amount">£{{ $listing->rent_period == 'week' ? (round($listing->rent_value) . 'pw') : (round($listing->rent_value * 52 / 12) . 'pcm') }}</span>

                        <div class="description">
                            {{ $listing->short_description }}
                        </div>

                        <ul class="listing-icons">
                            <li title="{{ $listing->bedrooms == 0 ? 'Studio (1 bedroom)' : ($listing->bedrooms . ($listing->bedrooms > 1 ? ' bedrooms' : ' bedroom')) }}"><i class="fa fa-bed fa-pad-5"></i>{{ $listing->bedrooms == 0 ? 'Studio' : $listing->bedrooms }}</li>
                            <li title="{{ $listing->bathrooms . ($listing->bathrooms > 1 ? ' bathrooms' : ' bathroom') }}"><i class="fa fa-bath fa-pad-5"></i>{{ $listing->bathrooms }}</li>
                            @if ($listing->town_distance != null)<li title="{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') . ' from ' . $listing->area->name . ' centre' }}"><i class="fa fa-map-marker fa-pad-5"></i>{{ $listing->town_distance . ($listing->town_distance > 1 ? ' mins' : ' min') }}</li>@endif
                            @if ($listing->furnished == true)<li title="Furnished"><i class="fa fa-check fa-pad-5"></i>Furnished</li>@endif
                            @if ($listing->bills_included == true)<li title="Bills included"><i class="fa fa-envelope-open fa-pad-5"></i>Bills Included</li>@endif
                            @if ($listing->pets_allowed == true)<li title="Pets allowed"><i class="fa fa-paw fa-pad-5"></i>Allowed</li>@endif
                        </ul>

                        <div class="listing-footer" style="height: auto">
                            <b>Address:</b>
                            <i>{{ $listing->address1 . ', ' . ($listing->address2 !== null ? $listing->address2 . ', ' : '') . $listing->town . ', ' . $listing->postcode }}</i>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('listings.destroy', $listing->id) }}" style="padding-top: 12px">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    @if (count($errors) > 0)
                        <div class="error">
                            @if (count($errors) == 1)
                                <i class="fa fa-warning fa-lg fa-pad-5"></i><b>Error:</b> {{ $errors->first() }}
                            @else
                                <i class="fa fa-warning fa-lg fa-pad-5"></i><b>Errors:</b>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <p>Are you sure you want to <b>permanently delete</b> this listing?</p>

                    <label for="delete-confirmation"><input type="checkbox" name="confirmation" id="delete-confirmation" required/>Yes, delete this listing</label>

                    <p class="small-text space-top">If you would like to temporarily hide this listing from search results, please <a href="#" class="deactivation-link" listing-id={{ $listing->id }}><i class="fa fa-power-off fa-pad-5 sr-icons"></i>deactivate</a> this listing instead.</p>
                    <label for="title">Re-enter Your Password:</label>
                    <input type="password" name="password" placeholder="Password" required/>


                    <button class="btn btn-primary btn-xl space-top" id="save-button">Delete</button>
                    <p class="small-text"><i><b>Warning:</b> This action cannot be reversed!</i></p>

            </div>

            <div class="col-lg-4 no-float"></div>


        </div>
    </div>

    @include('scripts.activationlinks')

@endsection