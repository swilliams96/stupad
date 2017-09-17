@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row" style="margin: 0; height: 100%; display: table-row;">

            @include('common.dashboardsidebar', ['page' => 'profile'])


            <!-- MAIN PAGE -->
            <div class="col-lg-9 mainpage no-float">
                <h1>Account Details</h1>
                <form method="POST">
                    {{ csrf_field() }}
                    <label for="firstname">First Name:</label>
                    <input type="text" name="firstname" value="{{ Auth::user()->first_name }}"/>
                    <label for="lastname">Last Name:</label>
                    <input type="text" name="lastname" value="{{ Auth::user()->last_name }}"/>
                    <label for="email">Email Address:</label>
                    <input type="text" name="email" value="{{ Auth::user()->email }}"/>
                    <button class="btn btn-primary btn-xl">Save</button>
                </form>

                <h1>Change Your Password</h1>
                <form action='/dashboard/updatepassword' method="POST">
                    {{ csrf_field() }}
                    <input type="password" name="old-password" id="old-password" placeholder="Current Password" style="margin-bottom: 10px;"/>
                    <input type="password" name="password" id="password" placeholder="New Password"/>
                    <input type="password" name="password_confirmation" id="password-confirm" placeholder="Confirm New Password"/>
                    <span id="password-helper-text">* Must be at least 8 characters long.</span>
                    <button class="btn btn-primary btn-xl">Update</button>
                </form>

            </div>

        </div>
    </div>

    @include('scripts.passwordvalidation')

@endsection