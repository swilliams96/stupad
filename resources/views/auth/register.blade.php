@extends('layouts.app')

@section('content')

    <section id="register">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-8 col-lg-offset-2">
                    <div class="login-box">
                        <h1>Register<i class="fa fa-group fa-pad-5l"></i></h1>
                        @if (count($errors) > 0)
                        <div class="error">
                            <i class="fa fa-warning fa-lg fa-pad-5"></i><b>Error:</b> {{ $errors->first() }}
                        </div>
                        @endif
                        <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}

                            <input type="text" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus/>
                            <input type="text" name="first-name" placeholder="First Name" value="{{ old('first-name') }}" required/>
                            <input type="text" name="last-name" placeholder="Last Name" value="{{ old('last-name') }}" required/>

                            <!--<input type="text" id="university" name="university" placeholder="University" style="margin-top: 20px;"/>-->


                            <input type="password" name="password" id="password" placeholder="Password" style="margin-top: 20px;" required/>
                            <input type="password" name="password_confirmation" id="password-confirm" placeholder="Confirm Password" required/>
                            <span id="password-helper-text">* Must be at least 8 characters long.</span>

                            <div class="row register-option-box" style="margin-top: 20px;">
                                <div class="col-md-5 col-md-offset-1"><input type="radio" id="account-type-student" name="account-type" value="student" {{ old('account-type') == 'student' ? 'checked="checked"' : '' }}/><label for="account-type-student">I am a student.</label></div>
                                <div class="col-md-5"><input type="radio" id="account-type-landlord" name="account-type" value="landlord" {{ old('account-type') == 'landlord' ? 'checked="checked"' : '' }}/><label for="account-type-landlord">I am a property owner.</label></div>
                            </div>

                            <input type="checkbox" id="terms" name="terms" required/>
                            <label for="terms">I agree to the <a href="{{ route('termsandconditions') }}" target="_blank">Terms and Conditions</a> and the <a href="{{ route('privacypolicy') }}" target="_blank">Privacy Policy</a>.</label>
                            <button class="btn btn-primary btn-xl">Register</button>
                        </form>
                        <span class="register-login-prompt">Already have an account? <a href="{{ route('login') }}">Log In</a> here.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('scripts.passwordvalidation')

@endsection
