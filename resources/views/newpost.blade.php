@extends('template')

@section('title', 'New Post!')

@section('page-content')
<div class="container">
    <div class="row">
    	 {!! Form::open(['url' => 'createpost']) !!}

        <div class="input-field">
            {!! Form::label('title', 'Title:') !!}
            {!! Form::text('title', null, ['class' => '']) !!}
        </div>
         <div class="input-field">
            {!! Form::textarea('text', null, ['class' => 'materialize-textarea']) !!}
        </div>
        <button class="btn btn-success" type="submit">Post!</button>
        {!! Form::close() !!}
    </div>

</div>
@stop

