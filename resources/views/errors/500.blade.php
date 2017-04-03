@extends('template')
@section('page-content')
<div class="container-fluid">
    <div class="row">
	<div class="col s8 offset-s2">
    	    <h3>We are so sorry :(</h3>
    	    <h5>It seems we have encountered an error.</h5>
    	    <h5>Our engineers are trying to fix it.</h5>
    	    <h5>Please try again in awhile.</h5>
	    <h6>{{ $exception->getMessage() }}</h6>
	</div>
    </div>
</div>
@stop
