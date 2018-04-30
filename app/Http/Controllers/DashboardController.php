<?php

namespace App\Http\Controllers;

use App\Listing;
use App\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }


    // PROFILE
    public function profile(Request $request) {
        return view('profile');
    }

    public function updateprofile(Request $request) {
        $this->validate($request, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = $request->user();
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->updated_at = Carbon::now();
        $user->save();
        return view('profile')->with('result', 'success_profile');
    }

    public function updatepassword(Request $request) {
        $this->validate($request, [
            'old-password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $old_password = $request->get('old-password');

        if (Hash::check($old_password, Auth::user()->password)) {
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->get('password'));
            $user->updated_at = Carbon::now();
            $user->save();
            return view('profile')->with('result', 'success_password');
        }

        return back()->withErrors()->with('results', 'fail_password');
    }


    // LISTINGS
    public function mylistings(Request $request) {
        $activelistings = Auth::user()->activelistings;
        $inactivelistings = Auth::user()->inactivelistings;
        return view('mylistings')
            ->with('listings_active', $activelistings)
            ->with('listings_inactive', $inactivelistings);
    }

    public function newlisting(Request $request) {
        if (!Auth::user()->landlord)
            return redirect(route('savedlistings'));

        $contact_prefs = DB::table('contact_prefs')->get();

        return view('newlisting', ['contact_prefs' => $contact_prefs]);
    }

    public function editlisting(Request $request, $id) {
        $listing = Listing::find($id);

        if ($listing == null || Auth::user() != $listing->owner)
            return redirect(route('mylistings'));

        $contact_prefs = DB::table('contact_prefs')->get();

        return view('editlisting')
            ->with('listing', $listing)
            ->with('contact_prefs', $contact_prefs);
    }

    public function deletelisting(Request $request, $id) {
        $listing = Listing::find($id);

        if ($listing == null || Auth::user() != $listing->owner)
            return redirect(route('mylistings'));

        return view('deletelisting')
            ->with('listing', $listing);
    }

    public function savedlistings(Request $request) {
        $savedlistings = collect(Auth::user()->savedlistings)->map(function ($record) {
            return Listing::find($record->listing);
        });

        return view('savedlistings')
            ->with('savedlistings', $savedlistings);
    }


    // MESSAGES
    public function messages(Request $request) {
        $chats = $request->user()->messages()->sortByDesc('sent_at')->groupBy('other')->all();
        return view('messagelist')->with('chats', $chats);
    }

    public function viewmessage(Request $request, $otherid=null) {
        if (is_null($otherid)) {
            return redirect('messages');
        } else {
            $other = User::find($otherid);
            if (!$other)
                return redirect('messages');

            $messages = $request->user()->messages($otherid);

            // TODO: mark all unseen messages in this chat as seen

            // Split the messages up by encoded newlines, ready to render as paragraphs...
            foreach ($messages as $message) {
                $message->message = $this->decompress($message->message);
            }

            return view('message')
                ->with('other', $other)
                ->with('user', $request->user())
                ->with('messages', $messages);
        }
    }

    public function sendmessage(Request $request, $otherid) {
        if (!Auth::check()) {
            return response()->json([
                'status' => 402,
                'message' => 'Not logged in.',
            ]);
        }

        $other = User::find($otherid);

        if (!$other) {
            return response()->json([
                'status' => 404,
                'message' => 'No user with this ID could be found.',
            ]);
        }

        $msg = $request->message;

        if (strlen($msg) == 0) {
            return response()->json([
                'status' => 400,
                'message' => 'There was no message.',
            ]);
        }

        $message = new Message();
        $message->from = Auth::id();
        $message->to = $otherid;
        $message->sent_at = Carbon::now();
        $message->seen_at = null;
        $message->message = $this->compress($msg);
        $message->save();

        return response()->json([
            'status' => 201,
            'message' => $message->message,
        ]);
    }


    // SUPPORT FUNCTIONS

    function compress(string $str) {
        $str = trim($str);
        $str = str_replace("\r\n", '\n', $str);                     // Escape linebreaks
        $str = str_replace("\r", '\n', $str);                     // Escape linebreaks
        $str = str_replace("\n", '\n', $str);                     // Escape linebreaks
        $str = preg_replace('/\s+/', ' ', $str);                // Single spaces + trim
        //$str = preg_replace('/(\\n)+/', '\n', $str);            // Single linebreaks
        return $str;
    }

    function decompress(string $str) {
        $parts = explode('\n', $str);
        $parts = array_filter($parts, 'strlen');          // Remove empty paragraphs from $parts
        return $parts;
    }
}
