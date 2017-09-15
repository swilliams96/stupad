<?php

namespace App\Http\Controllers;

use App\Listing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function show(Request $request, $listing_id) {
        $listing = Listing::find($listing_id);
        $active = true;

        if ($listing == null) {
            return 'No listing found.';
        }

        $active_carbon = new Carbon($listing->active_datetime);
        $inactive_carbon = new Carbon($listing->inactive_datetime);

        if ($inactive_carbon <= Carbon::now() || $active_carbon >= Carbon::now()) $active = false;

        return view('listing')
            ->with('listing', $listing)
            ->with('active', $active);
        // TODO: ->with('listing_images', $listing_images); from listingimages table
    }
}
