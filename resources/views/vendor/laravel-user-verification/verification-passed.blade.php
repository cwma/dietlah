@extends('template')

@section('title', 'Verification Successful')

@section('page-content')
<div class="container">
    <div class="row">
        <div class="container profile-container">
            <div class="card card-panel">
                <h2 class="header center">Verification Successful</h2>
                <div class="panel-body">
                    <div class="row">
                        <p class="center" class="col-md-4 control-label">Your email has been successfully verified.</p>                   
                        <br>
                    </div>
                    <div class="row center">
                        <a class="btn waves-effect waves-ligh light-green lighten-1" href="{{ route('login') }}" style="margin-top:5px">
                            Login now!
                        </a>
                    </row>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
