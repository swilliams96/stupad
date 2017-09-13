<?php

namespace App\Http\Controllers;

use App\Area;
use App\Listing;
use App\University;
use Illuminate\Support\Facades\App;
use Validator;
use DB;
use Cookie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;


class SearchController extends Controller
{

    public function search(Request $request) {
        return view('search')
            ->with('location', $request->location)
            ->with('all_locations', self::getlocations());
    }


    public function results(Request $request) {
        // Cache form variables
        $request_location = $request->location;
        $rent_min = $request->rent_min;
        $rent_max = $request->rent_max;
        $bedrooms_min = $request->bedrooms_min;
        $bedrooms_max = $request->bedrooms_max;
        $bathrooms_min = $request->bathrooms_min;
        $bathrooms_max = $request->bathrooms_max;
        $distance = $request->distance;
        $place = $request->place;

        // TODO: Integrate tables for ids and corresponding labels (studio, 1 bedroom, 2 bedrooms, etc.) instead of hardcoding
        $validator = Validator::make($request->all(), [
            'location' => 'bail|required|max:255',
            'rent_min' => 'required|integer|min:5|max:' . $rent_min,
            'rent_max' => 'required|integer|max:500|min:' . $rent_max,
            'bedrooms_min' => 'required|integer|min:0|max:' . $bedrooms_max,
            'bedrooms_max' => 'required|integer|max:5|min:' . $bedrooms_min,
            'bathrooms_min' => 'required|integer|min:1|max:' . $bathrooms_max,
            'bathrooms_max' => 'required|integer|max:5|min:' . $bathrooms_min,
            'distance' => 'required|integer|min:10|max:60',
            'place' => 'required|string|in:campus,town',
        ]);

        if ($validator->fails()) {
            return 'failed validator';
        }

        $COOKIE_LIFETIME_DAYS = 30;
        Cookie::queue('lastsearch_location', $request_location, 1440 * $COOKIE_LIFETIME_DAYS);
        Cookie::queue('lastsearch_rent_min', $rent_min, 1440 * $COOKIE_LIFETIME_DAYS);
        Cookie::queue('lastsearch_rent_max', $rent_max, 1440 * $COOKIE_LIFETIME_DAYS);
        Cookie::queue('lastsearch_bedrooms_min', $bedrooms_min, 1440 * $COOKIE_LIFETIME_DAYS);
        Cookie::queue('lastsearch_bedrooms_max', $bedrooms_max, 1440 * $COOKIE_LIFETIME_DAYS);
        Cookie::queue('lastsearch_bathrooms_min', $bathrooms_min, 1440 * $COOKIE_LIFETIME_DAYS);
        Cookie::queue('lastsearch_bathrooms_max', $bathrooms_max, 1440 * $COOKIE_LIFETIME_DAYS);
        Cookie::queue('lastsearch_distance', $distance, 1440 * $COOKIE_LIFETIME_DAYS);
        Cookie::queue('lastsearch_place', $place, 1440 * $COOKIE_LIFETIME_DAYS);

        $loc = University::where('name', 'like', $request_location)
            ->orWhere('short_name', 'like', $request_location)
            ->orWhere('slug', 'like', $request_location)
            ->first();
        if ($loc == null) return 'no valid location found'; // TODO: redirect to "did you mean X" page

        return redirect('/results/' . $loc->slug)
            ->with('rent_min', $rent_min)
            ->with('rent_max', $rent_max)
            ->with('bedrooms_min', $bedrooms_min)
            ->with('bedrooms_max', $bedrooms_max)
            ->with('bathrooms_min', $bathrooms_min)
            ->with('bathrooms_max', $bathrooms_max)
            ->with('distance', $distance)
            ->with('place', $place);
    }


    public function showresults(Request $request, $location_slug) {
        // Cache search variables (from session if they exist, if not use cookies of last search)
        $rent_min = $request->session()->get('rent_min', $request->cookie('lastsearch_rent_min'));
        $rent_max = $request->session()->get('rent_max', $request->cookie('lastsearch_rent_max'));
        $bedrooms_min = $request->session()->get('bedrooms_min', $request->cookie('lastsearch_bedrooms_min'));
        $bedrooms_max = $request->session()->get('bedrooms_max', $request->cookie('lastsearch_bedrooms_max'));
        $bathrooms_min = $request->session()->get('bathrooms_min', $request->cookie('lastsearch_bathrooms_min'));
        $bathrooms_max = $request->session()->get('bathrooms_max', $request->cookie('lastsearch_bathrooms_max'));
        $distance = $request->session()->get('distance', $request->cookie('lastsearch_distance'));
        $place = $request->session()->get('place', $request->cookie('lastsearch_place'));

        // Get active listings that fit our search criteria
        $uni = University::where('slug', $location_slug)->first();
        if($uni == null) return redirect('/search');
        $listings = Listing::where('area_id', $uni->area->id)
            ->where('active_datetime', '<=', Carbon::now())
            ->where('inactive_datetime', '>=', Carbon::now())
            ->whereBetween('rent_value', [$rent_min, $rent_max])
            ->whereBetween('bedrooms', [$bedrooms_min, $bedrooms_max])
            ->whereBetween('bathrooms', [$bathrooms_min, $bathrooms_max])
            ->where(function($query) use ($distance) {
                $query->where('town_distance', '<=', $distance);
                $query->orWhereNull('town_distance');
            })->get();

        return view('results')
            ->with('listings', $listings)
            ->with('location_name', $uni->name)
            ->with('area_name', $uni->area->name);
    }

    public static function getlocations() {
        return University::where('active', true)->pluck('name');
    }
}
