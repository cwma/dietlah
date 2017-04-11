@extends('template')

@section('title', 'Verification Failed')

@section('page-content')
<div class="container">
    <div class="row">
        <div class="container profile-container">
            <div class="card card-panel">
                <h2 class="header center">Verification Failed</h2>
                <div class="panel-body">
                    <div class="row">
                        <p class="center" class="col-md-4 control-label">We were not able to verify your email address. Please check that the link you clicked is valid. If you requested multiple verification emails, please use the latest one.</p>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
