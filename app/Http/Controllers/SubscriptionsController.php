<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionsController extends Controller
{
    public function subscribe(Request $request, $campaign) {
        $email = (Auth::check() ? Auth::user()->email : $request->email);
        if ($email === null)
            return response('No email supplied.', 500);

        // TODO : Subscribe

        return response('Successfully subscribed to campaign ' . $campaign);
    }
}
