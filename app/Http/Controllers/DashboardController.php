<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function showprofile(Request $request) {
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
}