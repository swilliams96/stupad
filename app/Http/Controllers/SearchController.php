<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Validator;
use DB;
use Cookie;
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
        $request_location = strtolower($request->location);
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
            return back();
        }

        // Store our last search in cookies to ensure the search is saved for a day (1440 minutes)
        Cookie::queue('lastsearch_location', $request_location, 1440);
        Cookie::queue('lastsearch_rent_min', $rent_min, 1440);
        Cookie::queue('lastsearch_rent_max', $rent_max, 1440);
        Cookie::queue('lastsearch_bedrooms_min', $bedrooms_min, 1440);
        Cookie::queue('lastsearch_bedrooms_max', $bedrooms_max, 1440);
        Cookie::queue('lastsearch_bathrooms_min', $bathrooms_min, 1440);
        Cookie::queue('lastsearch_bathrooms_max', $bathrooms_max, 1440);
        Cookie::queue('lastsearch_distance', $distance, 1440);
        Cookie::queue('lastsearch_place', $place, 1440);

        // For local development environment debugging:
        // $location_list = array();
        //  $found_slug = 'slug';

        $location_list = DB::table('locations')->select('name', 'short_name', 'slug')->where('active', true)->get();

        foreach ($location_list as $row) {
            if (strtolower($row->name) == $request_location || strtolower($row->short_name) == $request_location) {
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


    public function showresults(Request $request, $locationslug) {
        return 'showing results for ' . $locationslug . '...<br/><br/></br><b>SESSION DATA:</b>'
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
    }

    public static function getlocations() {
        if (App::environment('local')) {
            $names = ['University of Bath', 'Bath Spa University', 'University of Bristol', 'University of West England', 'Bath Town', 'Bristol Town'];
            return $names;
        }
        return DB::table('locations')->where('active', true)->pluck('name');
    }
}
