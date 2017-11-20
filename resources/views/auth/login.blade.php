@extends('layouts.app')

@section('content')

    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="login-box">
                        <h1>Log In<i class="fa fa-user-circle-o fa-pad-5l"></i></h1>
                        @if (count($errors) > 0)
                            <div class="error">
                                <i class="fa fa-warning fa-lg fa-pad-5"></i><b>Error:</b> {{ $errors->first() }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <input type="text" id="email" name="email" type="email" placeholder="Email Address" {{ $errors->has('email') ? 'class="has-error"' : '' }} required autofocus/>
                            <input type="password" id="password" name="password" placeholder="Password" required/>
                            <label style="line-height: 10px;"><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me</label>
                            <button class="btn btn-primary btn-xl">Log In</button>
                        </form>
                        <span class="register-login-prompt">Don't have an account yet? <a href="{{ route('register') }}">Register</a> here.<br/>
                            <i><a href="{{ route('password.request') }}" style="padding-left: 10px;">Forgot your password?</a></i></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
