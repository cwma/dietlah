@extends('template')

@section('title')
{{$post['title']}}
@stop

@section('page-content')
<div class="container">
<h2>{{$post['title']}}</h2>
<p><b>by Author</b></p>
<hr>
    <div class="row">
    {{$post['text']}}
    </div>

    @if (Auth::check() && Auth::user()->id == $post['user_id'])
        {!! Form::open(['url' => 'deletepost']) !!}
        {!! Form::hidden('post_id', $post['id'], ['class' => 'form-control']) !!}
            <button class="btn btn-success" type="submit">delete</button>
        {!! Form::close() !!}
    @endif
    @if (count($post->comments))
    	<div class="row">
    	@foreach($post->comments as $comment)
    		<p>{{$comment['comment']}} by {{$comment->user->username}}</p>
    	@endforeach
    	</div>
    @endif
   <div class="row">
    	 {!! Form::open(['action' => ['CommentController@createComment', $post->id]]) !!}

        <div class="input-field">
            {!! Form::label('comment', 'Say something...') !!}
            {!! Form::text('comment', null, ['class' => '']) !!}
        </div>
        <button class="btn btn-success" type="submit">Comment...</button>
        {!! Form::close() !!}
    </div>

</div>
@stop