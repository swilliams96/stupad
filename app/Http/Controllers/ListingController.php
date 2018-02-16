<?php

namespace App\Http\Controllers;

use App\Area;
use App\Listing;
use App\ListingImage;
use App\SavedListing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\Rule;
use function MongoDB\BSON\toJSON;
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
            'contact_prefs' => 'required|integer|min:1',
            'contact_phone' => 'required_without:contact_email|nullable|string|digits_between:10,11|numeric|regex:/^(0)[0-9]+$/|bail',
            'contact_email' => 'nullable|string|email',
            'description' => 'required|string|max:4096',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ],
        [
            'title.max' => 'Your title is too long! Please use less than 64 characters.',
            'postcode.*' => 'Please enter a valid UK postcode.',
            'contact_phone.required_without' => 'Please enter a contact phone number or email address.',
            'contact_phone.digits_between' => 'Please enter a valid UK contact phone number.',
            'contact_phone.numeric' => 'Please enter a valid UK contact phone number.',
            'contact_phone.regex' => 'Please enter a valid UK contact phone number.',
            'contact_email.email' => 'Please enter a valid contact email address.',
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

        $pcjson = @file_get_contents('http://api.postcodes.io/postcodes/' . rawurlencode($request->postcode));
        if (!$pcjson) {
            $bag = new MessageBag();
            $bag->add('postcode.notfound', 'Postcode could not be found. Please check and try again. If you continue to have issues please contact support.');
            return back()->withInput()->with('errors', session()->get('errors', new ViewErrorBag())->put('default', $bag));
        }

        $postcodeio = json_decode($pcjson);
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
            $bag->add('postcode.notfound', 'There was an error processing your postcode. Please check it and try again. If you continue to have issues please contact support.');
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
            'contact_prefs' => $request->contact_prefs,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
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
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id, $slug = null)
    {
        if ($slug !== null) {
            if (in_array($slug, ['activate', 'deactivate']))
                return redirect(route('mylistings'));
        }

        $listing = Listing::find($id);
        $active = true;

        if ($listing == null) {
            // TODO: 404
            return 'No listing found.';
        }

        $description = explode('\n', $listing->description);

        $active_carbon = new Carbon($listing->active_datetime);
        $inactive_carbon = new Carbon($listing->inactive_datetime);

        if ($inactive_carbon <= Carbon::now() || $active_carbon >= Carbon::now()) $active = false;

        $record = SavedListing::where('user', '=', Auth::id())
            ->where('listing', '=', $id)
            ->whereNull('unsaved_datetime')
            ->first();

        $saved = ($record == null ? false : true);

        $sharebuttonshtml = view('common.sharebuttons')->render();
        $sharebuttonshtml = str_replace('"', '&quot;', $sharebuttonshtml);
        $sharebuttonshtml = str_replace(["\n", "\r", '    '], '', $sharebuttonshtml);
        $sharebuttonshtml = str_replace('SHARE_URL', 'stupad.co.uk/listings/' . $listing->id, $sharebuttonshtml);
        $sharebuttonshtml = str_replace('SHARE_TITLE', $listing->title, $sharebuttonshtml);
        $sharebuttonshtml = str_replace('SHARE_IMG_URL', $listing->header->file(), $sharebuttonshtml);
        $sharebuttonshtml = str_replace('SHARE_TEXT', 'Check out this great house I found on StuPad!', $sharebuttonshtml);
        $sharebuttonshtml = str_replace('SHARE_SHORT_DESC', $listing->short_description, $sharebuttonshtml);

        return view('listing')
            ->with('listing', $listing)
            ->with('description', $description)
            ->with('active', $active)
            ->with('saved', $saved)
            ->with('sharebuttonshtml', $sharebuttonshtml);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect('/dashboard/listings/edit/' . $id);
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:64',
            'rent_value' => 'required|integer|min:0',
            'rent_period' => [
                'required',
                'string',
                Rule::in(['week', 'month']),
            ],
            'bedrooms' => 'required|integer|min:0|max:8',
            'bathrooms' => 'required|integer|min:0|max:6',
            'furnished' => 'required|boolean',
            'bills' => 'required|boolean',
            'pets' => 'required|boolean',
            'contact_prefs' => 'required|integer|min:1',
            'contact_phone' => 'required_without:contact_email|nullable|string|digits_between:10,11|numeric|regex:/^(0)[0-9]+$/|bail',
            'contact_email' => 'nullable|string|email',
            'description' => 'required|string|max:4096',
            'header_image' => 'required|integer|min:0',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ],
        [
            'title.max' => 'Your title is too long! Please use less than 64 characters.',
            'postcode.*' => 'Please enter a valid UK postcode.',
            'contact_phone.required_without' => 'Please enter a contact phone number or email address.',
            'contact_phone.digits_between' => 'Please enter a valid UK contact phone number.',
            'contact_phone.numeric' => 'Please enter a valid UK contact phone number.',
            'contact_phone.regex' => 'Please enter a valid UK contact phone number.',
            'contact_email.email' => 'Please enter a valid contact email address.',
            'description.max' => 'Your description is too long! Please use less than 4096 characters.',
            'images.*.image' => 'Image uploads must be either JPEG or PNG format.',
            'images.*.mimes' => 'Image uploads must be either JPEG or PNG format.',
            'images.*.max' => 'Image uploads must be less than 5MB in size.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $listing = Listing::find($id);

        if (Auth::user() != $listing->owner || $listing == null)
            return redirect(route('mylistings'));

        // Manually check file extensions of uploaded files
        if ($request->images !== null) {
            foreach ($request->file('images') as $file) {
                $ext = $file->clientExtension();
                if (!in_array($ext, ['jpeg', 'jpg', 'png'])) {
                    $bag = new MessageBag();
                    $bag->add('images.invalidext', 'Please ensure images are in either JPEG or PNG format.');
                    return back()->withInput()->with('errors', session()->get('errors', new ViewErrorBag())->put('default', $bag));
                }
            }
        }

        // Cache the image that we want to set as the header image in case we change the image_number later
        if ($request->header_image != 0)
            $header_image_name = $listing->images()->where('image_number', $request->header_image)->value('file_name');

        // If no new images have been uploaded then make sure we haven't deleted every existing image
        if ($request->images == null) {
            $all_deleted = true;
            for ($i = 1; $i <= count($listing->images); $i++)
                if ($request->get('existingimages_' . $i . '_deleted') == null)
                    $all_deleted = false;

            if ($all_deleted) {
                $bag = new MessageBag();
                $bag->add('images.deletedall', 'Listings must have a header image. Please select an image that has not been deleted or upload a new one.');
                return back()->withInput()->with('errors', session()->get('errors', new ViewErrorBag())->put('default', $bag));
            }
        }

        // Update the listing variables (except header image)
        if ($request->rent_period == 'month')
            $request->rent_value = round($request->rent_value * 12 / 52, 2);

        $short_description = $this->summarise($request->description);
        $description = str_replace(["\r\n", "\r", "\n"], '\n', $request->description);

        // SAVE LISTING
        $listing->title = $request->title;
        $listing->rent_value = $request->rent_value;
        $listing->rent_period = $request->rent_period;
        $listing->description = $description;
        $listing->short_description = $short_description;
        $listing->bedrooms = $request->bedrooms;
        $listing->bathrooms = $request->bathrooms;
        $listing->furnished = $request->furnished;
        $listing->bills_included = $request->bills;
        $listing->pets_allowed = $request->pets;
        $listing->contact_prefs = $request->contact_prefs;
        $listing->contact_phone = $request->contact_phone;
        $listing->contact_email = $request->contact_email;
        $listing->saveOrFail();

        // Delete images that were flagged for deletion
        for ($i = 1; $i <= count($listing->images); $i++) {
            if ($request->get('existingimages_' . $i . '_deleted') != null) {
                $listing->images()->where('image_number', $i)->delete();
            }
        }

        $listing = Listing::find($id);      // Refresh our listing model

        // Recalculate existing listing image_number values
        $i = 1;
        $images = $listing->images;
        foreach($images as $image) {
            $image->image_number = $i;
            $image->save();
            $i++;
        }

        // Upload any new images with image_number calculated as next lowest available integer
        if ($request->images !== null) {
            foreach ($request->file('images') as $file) {
                $ext = $file->clientExtension();
                $name = $this->new_guid() . '.' . $ext;
                $path = Storage::disk('public')->putFileAs('listing_images/' . $listing->id, $file, $name);

                if (is_string($path)) {
                    $listing->images()->create([
                        'image_number' => $i,
                        'file_name' => $name,
                    ]);
                    $i++;
                } else {
                    $listing->images()->where('file_name', $name)->delete();
                    return 'An error occurred:<br/>$path=' . $path . '<br/>$name=' . $name . '<br/>$i=' . $i;
                }
            }
        }

        // Set header_image to either the supplied image_number or otherwise just to the first image
        $request->header_image == 0
            ? $listing->header_image = 1
            : $listing->header_image = $listing->images()->where('file_name', $header_image_name)->value('image_number');
        $listing->save();

        return redirect(route('mylistings'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'confirmation' => 'required|accepted',
            'password' => 'required|string',
        ],
            [
                'confirmation.accepted' => 'Please confirm that you would like to delete this listing.',
            ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        if (!Hash::check($request->password, Auth::user()->getAuthPassword())) {
            $bag = new MessageBag();
            $bag->add('password.incorrect', 'Incorrect password.');
            return back()->withInput()->with('errors', session()->get('errors', new ViewErrorBag())->put('default', $bag));
        }

        $listing = Listing::find($id);

        if (Auth::user() != $listing->owner || $listing == null)
            return redirect(route('mylistings'));


        // Delete the images, then the listing!
        $success_images = $listing->images()->delete();
        $success_listing = $listing->delete();

        if (!$success_images || !$success_listing)
            return 'An error occured when deleting this listing. Please contact support!';


        return redirect(route('mylistings'));
    }


    // LISTING ACTIVATION

    public function activate(Request $request, $id) {
        $listing = Listing::findOrFail($id);
        if (Auth::user() == $listing->owner) {
            $listing->active_datetime = Carbon::now();
            if ($listing->inactive_datetime == null || $listing->inactive_datetime <= Carbon::now()->addHours(env('LISTING_RENEW_HOURS_BEFORE', 24))) {
                $listing->inactive_datetime = Carbon::now()->addDays(env('LISTING_RENEW_DAYS', 14));
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

    public function renew(Request $request, $id) {
        $listing = Listing::findOrFail($id);
        if (Auth::user() == $listing->owner) {
            $listing->active_datetime = Carbon::now();
            if ($listing->inactive_datetime == null || $listing->inactive_datetime <= Carbon::now()->addHours(env('LISTING_RENEW_HOURS_BEFORE', 24))) {
                $listing->inactive_datetime = Carbon::parse($listing->inactive_datetime)->addDays(env('LISTING_RENEW_DAYS', 14));
            }
            $listing->save();
        }
        return redirect(route('mylistings'));
    }


    // FAVOURITE LISTINGS

    public function save(Request $request, $id) {
        if (!Auth::check()) {
            return response()->json([
                'status' => 402,
                'message' => 'Not logged in.',
            ]);
        }

        $record = SavedListing::where('user', '=', $request->user()->id)
            ->where('listing', '=', $id)
            ->first();

        if ($record == null) {
            $record = $request->user()->savedlistings()->create([
                'user' => $request->user()->id,
                'listing' => $id,
                'saved_datetime' => Carbon::now(),
                'unsaved_datetime' => null,
            ]);
        } else {
            $record->saved_datetime = Carbon::now();
            $record->unsaved_datetime = null;
            $record->saveOrFail();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Successfully saved listing.',
        ]);
    }

    public function unsave(Request $request, $id) {
        if (!Auth::check()) {
            return response()->json([
                'status' => 402,
                'message' => 'Not logged in.',
            ]);
        }

        $record = SavedListing::where('user', '=', $request->user()->id)
            ->where('listing', '=', $id)
            ->first();

        if ($record != null) {
            $record->unsaved_datetime = Carbon::now();
            $record->saveOrFail();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Successfully unsaved listing.',
        ]);
    }





    // SUPPORT FUNCTIONS

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
