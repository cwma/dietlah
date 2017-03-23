@extends('template')

@section('title', "Edit Comment")

@section('page-content')
<div class="container">

    @if (Auth::check() && Auth::user()->id == $comment['user_id'])
   <div class="row">
    	 {!! Form::open(['url' => 'updatecomment']) !!}

        <div class="input-field">
            {!! Form::text('comment', $comment['comment'], ['class' => '']) !!}
            {!! Form::hidden('commentId', $comment['id'], ['class' => 'form-control']) !!}
            {!! Form::hidden('postId', $comment['post_id'], ['class' => 'form-control']) !!}
        </div>
        <button class="btn btn-success" type="submit">Update</button>
        {!! Form::close() !!}
    </div>
    @endif
</div>
@stop