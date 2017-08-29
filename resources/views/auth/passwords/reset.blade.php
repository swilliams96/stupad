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
                        <form method="POST" action="{{ route('password.request') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="email" id="email" name="email" placeholder="Email" value="{{ $email or old('email') }}" {{ $errors->has('email') ? 'class="has-error"' : '' }} required autofocus/>
                            <input id="password" type="password" name="password" placeholder="New Password" required>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password" required>
                            <span id="password-helper-text">* Must be at least 8 characters long.</span>
                            <button class="btn btn-primary btn-xl" style="margin-top: 15px; margin-bottom: 20px;">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <!-- Client side password validation -->
    <script type="text/javascript">
        function EquatePasswordFields() {
            if ($("#password").val() != "" && $("#password-confirm").val() != "") {
                if ($("#password-confirm").val() == $("#password").val()) {
                    $("#password-confirm").css("border", "1px solid #ccc");
                } else {
                    $("#password-confirm").css("border", "1px solid #A11");
                }
            }
        }

        $(function() {
            $('#password').keyup(function() {
                if ($("#password").val().length >= 8 || $("#password").val().length == 0) {
                    $("#password").css("border", "1px solid #ccc");
                    $("#password-helper-text").css("display", "none");
                } else {
                    $("#password-helper-text").css("display", "inline");
                    $("#password").css("border", "1px solid #A11");
                }
                EquatePasswordFields();
            });

            $('#password-confirm').keyup(function() {
                EquatePasswordFields();
            });
        });
    </script>

@endsection
