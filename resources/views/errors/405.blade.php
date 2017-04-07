@extends('template')

@section('title', 'Unauthorized')

@section('page-content')
<div class="container">
    <br><br>
    <h1 class="header center light-green-text"><img src="logo.png"></h1>
    <h1 class="header center light-green-text">thats not the right method!</h1>
    <div class="row center">
        <h5 class="header col s12 light">you're seeing this because you tried to access a url in a manner you're not supposed to</h5>
    </div>
</div>
@stop