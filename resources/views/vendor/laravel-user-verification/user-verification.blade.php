@extends('template')

@section('title', 'Email Verification')

@section('page-content')
<div class="container">
    <div class="row">
        <div class="container profile-container">
            <div class="card card-panel">
                <h2 class="header center">Email Verification</h2>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('verify.resend') }}">
                        {{ csrf_field() }}

                        <div class="row">
                            <p class="center" class="col-md-4 control-label">We've sent you an email containing a link to verify your email.</p>
                            <p class="center" class="col-md-4 control-label">Please verify your email for this account before continuing</p>
                            <p class="center" class="col-md-4 control-label">Unverified accounts will have read-only access.</p>
                            <br>
                        </div>

                        <div class="form-group">
                            <div class="row center">
                                <button type="submit" class="btn waves-effect waves-ligh light-green lighten-1" onclick="$('.nav-progress').show(); return true;">
                                    Resend Verification Link
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            @if ($errors->has('email')) 
                                <span class="help-block">
                                    <p class="center light-green-text"><strong>{{ $errors->first('email') }}</strong></p>
                                </span>
                            @endif
                        </div>
                        @if (session('status'))
                            <div class="row">
                                <p class="center light-green-text">{{ session('status') }}</p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
