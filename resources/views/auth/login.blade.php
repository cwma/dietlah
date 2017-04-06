@extends('template')

@section('page-content')
<div class="container">
    <div class="row">
        <div class="container profile-container">
            <div class="card card-panel">
                <h2 class="header center">Login</h2>
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">E-Mail Address</label>

                            <div class="input-field">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus class="validate">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">Password</label>

                            <div class="input-field">
                                <input id="password" type="password" class="form-control" name="password" required class="validate">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p>
                            <input type="checkbox" id="remember" name="remember" />
                            <label for="remember">Remember Me</label>
                        </p>

                        <br>

                        <div class="form-group">
                            <div class="row center">
                                <button type="submit" class="btn waves-effect waves-ligh light-green lighten-1" style="margin-top:5px">
                                    Login
                                </button>

                                <a class="btn waves-effect waves-ligh light-green lighten-1" href="{{ route('password.request') }}" style="margin-top:5px">
                                    Forgot Your Password?
                                </a>

                            </div>
                        </div>

                        <div class="row center">
                            <a class="btn waves-effect waves-ligh light-green lighten-1" href="{{ route('register') }}" style="margin-top:5px">
                                Don't have an account?
                            </a>
                        </row>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
