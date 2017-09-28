<?php

namespace App\Http\Controllers;

use App\Area;
use App\Listing;
use App\ListingImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\Rule;
use function Sodium\add;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect(route('search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect(route('newlisting'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:64',
            'rent_value' => 'required|integer|min:0',
            'rent_period' => [
                'required',
                'string',
                Rule::in(['week', 'month']),
            ],
            'address1' => 'required|string|max:64',
            'address2' => 'nullable|string|max:64',
            'town' => 'required|string|max:64',
            'postcode' => 'required|string|max:8|regex:/^[a-zA-Z]{1,2}\d[a-zA-Z0-9]? \d[a-zA-z]{2}$/',
            'bedrooms' => 'required|integer|min:0|max:8',
            'bathrooms' => 'required|integer|min:0|max:6',
            'furnished' => 'required|boolean',
            'bills' => 'required|boolean',
            'pets' => 'required|boolean',
            'description' => 'required|string|max:4096',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ],
        [
            'title.max' => 'Your title is too long! Please use less than 64 characters.',
            'postcode.*' => 'Please enter a valid UK postcode.',
            'description.max' => 'Your description is too long! Please use less than 4096 characters.',
            'images.*.image' => 'Image uploads must be either JPEG or PNG format.',
            'images.*.mimes' => 'Image uploads must be either JPEG or PNG format.',
            'images.*.max' => 'Image uploads must be less than 5MB in size.',
        ]);

        if ($validator->fails()) {
           return back()->withErrors($validator->errors())->withInput();
        }

        if ($request->rent_period == 'month')
            $request->rent_value = round($request->rent_value * 12 / 52, 2);

        $short_description = $this->summarise($request->description);
        $description = str_replace(["\r\n", "\r", "\n"], '\n', $request->description);

        $json = file_get_contents('http://api.postcodes.io/postcodes/' . rawurlencode($request->postcode));
        $postcodeio = json_decode($json);
        if ($postcodeio->status == 200) {
            $area = Area::where('admin_district', 'like', $postcodeio->result->admin_district)->first();
            if ($area == null) {                    // If we couldn't find it from the admin_district...
                foreach (Area::all() as $a) {       // ...roughly search by postcode list instead.
                    if ($area != null) break;
                    $postcode_list = explode(',', $a->postcode_list);
                    foreach ($postcode_list as $pc) {
                        if ($area != null) break;
                        if ($pc == null) break;
                        if (substr(strtoupper($postcodeio->result->postcode), 0, strlen($pc)) == strtoupper($pc)) {
                            $area = $a;
                            break;
                        }
                    }
                }
            }
        } else {
            $bag = new MessageBag();
            $bag->add('postcode.notfound', 'Postcode could not be found. Please check and try again. If you continue to have issues please contact support.');
            return back()->withInput()->with('errors', session()->get('errors', new ViewErrorBag())->put('default', $bag));
        }

        if ($area == null) {
            $bag = new MessageBag();
            $bag->add('area.invalid', 'The given postcode could not be matched to any of our active Universities. Please check the property you are listing is near to a University and try again. If you continue to have issues or believe this to be a mistake, please contact support.');
            return back()->withInput()->with('errors', session()->get('errors', new ViewErrorBag())->put('default', $bag));
        }

        $lat = $postcodeio->result->latitude;
        $lng = $postcodeio->result->longitude;

        if ($lat == null || $lng == null) {
            $bag = new MessageBag();
            $bag->add('postcode.notfound', 'Postcode location could not be found. Please check and try again. If you continue to have issues please contact support.');
            return back()->withInput()->with('errors', session()->get('errors', new ViewErrorBag())->put('default', $bag));
        }

        $listing = $request->user()->listings()->create([
            'title' => $request->title,
            'landlord_id' => $request->user()->id,
            'rent_value' => $request->rent_value,
            'rent_period' => $request->rent_period,
            'description' => $description,
            'short_description' => $short_description,
            'area_id' => $area->id,
            'lat' => $lat,
            'lng' => $lng,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            //'town_distance' => null,
            'furnished' => $request->furnished,
            'bills_included' => $request->bills,
            'pets_allowed' => $request->pets,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'town' => $request->town,
            'postcode' => $request->postcode,
            'header_image' => 1,
        ]);

        $i = 1;
        foreach ($request->file('images') as $file) {
            $ext = $file->clientExtension();
            if (!in_array($ext, ['jpeg', 'jpg', 'png'])) {
                $listing->images()->delete();
                $listing->delete();
                $bag = new MessageBag();
                $bag->add('images.invalidext', 'Please ensure images are in either JPEG or PNG format.');
                return back()->withInput()->with('errors', session()->get('errors', new ViewErrorBag())->put('default', $bag));
            }

            $name = $this->new_guid() . '.' . $ext;

            $path = Storage::disk('public')->putFileAs('listing_images/' . $listing->id, $file, $name);

            if (is_string($path)) {
                $listing->images()->create([
                    'image_number' => $i,
                    'file_name' => $name,
                ]);

                $i++;

            } else {
                $listing->images()->delete();
                $listing->delete();
                return 'an error occurred : $path=' . $path . ' : $name=' . $name . ' : $i=' . $i;
            }
        }

        return redirect(route('mylistings'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $slug)
    {
        if (isset($slug) && ($slug == 'activate' || $slug == 'deactivate'))
            return redirect(route('mylistings'));

        $listing = Listing::find($id);
        $active = true;

        if ($listing == null) {
            return 'No listing found.';
        }

        $description = explode('\n', $listing->description);

        $active_carbon = new Carbon($listing->active_datetime);
        $inactive_carbon = new Carbon($listing->inactive_datetime);

        if ($inactive_carbon <= Carbon::now() || $active_carbon >= Carbon::now()) $active = false;

        return view('listing')
            ->with('listing', $listing)
            ->with('description', $description)
            ->with('active', $active);
        // TODO: ->with('listing_images', $listing_images); from listingimages table
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activate(Request $request, $id) {
        $listing = Listing::findOrFail($id);
        if (Auth::user() == $listing->owner) {
            $listing->active_datetime = Carbon::now();
            if ($listing->inactive_datetime == null || $listing->inactive_datetime <= Carbon::now()->addDays(1)) {
                $listing->inactive_datetime = Carbon::now()->addDays(14);
            }
            $listing->save();
        }
        return redirect(route('mylistings'));
    }

    public function deactivate(Request $request, $id) {
        $listing = Listing::findOrFail($id);
        if (Auth::user() == $listing->owner) {
            $listing->active_datetime = null;
            $listing->save();
        }
        return redirect(route('mylistings'));
    }

    public function save(Request $request, $id) {
        // TODO: favourite listings
        return response('OK', 200);
    }



    function summarise(string $long, int $length = 190)
    {
        $long = preg_replace('/\s+/', ' ', trim($long));

        $short = substr($long, 0, $length);
        $short == $long ? $shortened = false : $shortened = true;

        $short = ucfirst($short);

        if ($shortened) {
            $parts = explode(' ', $short);
            $parts[count($parts)-1] = '...';
            $short = implode(' ', $parts);
        }

        return $short;
    }

    function new_guid() {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        return strtolower(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));
    }

}
