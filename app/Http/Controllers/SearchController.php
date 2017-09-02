<?php

namespace App\Http\Controllers;

use Validator;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;


class SearchController extends Controller
{

    public function search() {
        return view('search');
    }


    public function results(Request $request) {

        $rent_min = $request->rent_min;
        $rent_max = $request->rent_max;
        $bedrooms_min = $request->bedrooms_min;
        $bedrooms_max = $request->bedrooms_max;
        $bathrooms_min = $request->bathrooms_min;
        $bathrooms_max = $request->bathrooms_max;

        // TODO: Check if location is in the database table of valid locations
        // TODO: Integrate tables for ids and corresponding labels (studio, 1 bedroom, 2 bedrooms, etc.)
        $validator = Validator::make($request->all(), [
            'location' => 'bail|required|max:255',
            'rent_min' => 'required|integer|min:5|max:' . $rent_min,
            'rent_max' => 'required|integer|max:500|min:' . $rent_max,
            'bedrooms_min' => 'required|integer|min:0|max:' . $bedrooms_max,
            'bedrooms_max' => 'required|integer|max:5|min:' . $bedrooms_min,
            'bathrooms_min' => 'required|integer|min:1|max:' . $bathrooms_max,
            'bathrooms_max' => 'required|integer|max:5|min:' . $bathrooms_min,
            'distance' => 'required|integer|min:10|max:60',
            'institution' => 'required|string|in:campus,town',
        ]);

        if ($validator->fails()) {
            return back();
        }

        $request_location = $request->location;
        $location_list = DB::table('locations')->select('name', 'short_name', 'slug')->where('active', true)->get();

        foreach ($location_list as $row) {
            if ($row->name == $request_location) {  // Check full names first ...
                $found_slug = $row->slug;
                break;
            }
            if ($row->short_name == $request_location) {
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
                ->with('institution', $institution);
        }

        // TODO: redirect to 'did you mean X' page below
        return 'search page successfully posted<br/>no valid location found';
    }


    public function showresults(Request $request, $locationslug) {
        return 'showing results for ' . $locationslug . '...<br/><br/>'
            . 'rent min: £' . $request->session()->get('rent_min', 0)
            . '<br/>rent max: £' . $request->session()->get('rent_max', 0)
            . '<br/>bed min: £' . $request->session()->get('bedrooms_min', 0)
            . '<br/>bed max: £' . $request->session()->get('bedrooms_max', 0)
            . '<br/>bath min: £' . $request->session()->get('bathrooms_min', 0)
            . '<br/>bath max: £' . $request->session()->get('bathrooms_max', 0)
            . '<br/>distance: ' . $request->session()->get('distance', 0) . ' mins'
            . '    to ' . $request->session()->get('institution', 0);
    }
}
