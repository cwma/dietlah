@extends('template')

@section('title', 'New Post!')

@section('page-content')
<div class="container">
    <div class="row">
    	 {!! Form::open(['url' => 'createpost']) !!}

        <div class="form-group">
            {!! Form::label('title', 'Title:') !!}
            {!! Form::text('title', null, ['class' => 'form-control']) !!}
        </div>
         <div class="form-group">
            {!! Form::textarea('text', null, ['class' => 'form-control']) !!}
        </div>
        <button class="btn btn-success" type="submit">Post!</button>
        {!! Form::close() !!}
    </div>

</div>

