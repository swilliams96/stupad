<?php

namespace App\Http\Controllers;

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

        $location_list = DB::table('locations')->select('name', 'short_name', 'slug')->where('active', true)->get();

        $request_location = strtolower($request->location);
        foreach ($location_list as $row) {
            if ($request_location == strtolower($row->name) || $request_location == strtolower($row->short_name) || $request_location == strtolower($row->slug)) {
                $found_slug = $row->slug;
                break;
            }
        }

        if (isset($found_slug)) {
            return redirect('/results/' . $found_slug)
                ->with('rent_min', $rent_min)
                ->with('rent_max', $rent_max)
                ->with('bedrooms_min', $bedrooms_min)
                ->with('bedrooms_max', $bedrooms_max)
                ->with('bathrooms_min', $bathrooms_min)
                ->with('bathrooms_max', $bathrooms_max)
                ->with('distance', $distance)
                ->with('place', $place);
        }

        // TODO: redirect to 'did you mean X' page below
        return 'search page successfully posted<br/>no valid location found';
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
        $area_id = DB::table('locations')->where('slug', $location_slug)->value('area_id');
        if ($area_id == null) redirect('/search');
        $listings = DB::table('listings')
            ->where('area_id', $area_id)
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
            ->with('location_name', $location_slug);
        // TODO: find out proper area name using a new 'area' database where locations belong to areas and use this for 'location_name' instead of just the slug

        /*
        return 'showing results for ' . $location_slug . '...<br/><br/></br><b>SESSION DATA:</b>'
            . '<br/>rent min: £' . $request->session()->get('rent_min', 0)
            . '<br/>rent max: £' . $request->session()->get('rent_max', 0)
            . '<br/>bed min: ' . $request->session()->get('bedrooms_min', 0)
            . '<br/>bed max: ' . $request->session()->get('bedrooms_max', 0)
            . '<br/>bath min: ' . $request->session()->get('bathrooms_min', 0)
            . '<br/>bath max: ' . $request->session()->get('bathrooms_max', 0)
            . '<br/>distance: ' . $request->session()->get('distance', 0) . ' mins'
            . '    to ' . $request->session()->get('place', "")
            . '<br/><br/></br><b>COOKIE DATA:</b>'
            . '<br/>location: ' . $request->cookie('lastsearch_location')
            . '<br/>rent min: £' . $request->cookie('lastsearch_rent_min', 0)
            . '<br/>rent max: £' . $request->cookie('lastsearch_rent_max', 0)
            . '<br/>bed min: ' . $request->cookie('lastsearch_bedrooms_min', 0)
            . '<br/>bed max: ' . $request->cookie('lastsearch_bedrooms_max', 0)
            . '<br/>bath min: ' . $request->cookie('lastsearch_bathrooms_min', 0)
            . '<br/>bath max: ' . $request->cookie('lastsearch_bathrooms_max', 0)
            . '<br/>distance: ' . $request->cookie('lastsearch_distance', 0) . ' mins'
            . '    to ' . $request->cookie('lastsearch_place');
        */
    }

    public static function getlocations() {
        return DB::table('locations')->where('active', true)->pluck('name');
    }
}
