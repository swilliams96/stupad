@extends('layouts.app')

@section('content')

    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="login-box" style="min-height: 275px;">
                        <h1>Reset Password<i class="fa fa-user-circle-o fa-pad-5l"></i></h1>
                        @if (count($errors) > 0)
                        <div class="error">
                            <i class="fa fa-warning fa-lg fa-pad-5"></i><b>Error:</b> {{ $errors->first() }}
                        </div>
                        @elseif (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}
                            <input type="email" id="email" name="email" placeholder="Email" {{ $errors->has('email') ? 'class="has-error"' : '' }} required autofocus/>
                            <p>Please enter your email address above. You will be sent an email containing a link to set your new password.</p>
                            <button class="btn btn-primary btn-xl" style="margin-bottom: 25px;">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
