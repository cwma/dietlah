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
    		<p>"{{$comment['comment']}}" by {{$comment->user->username}}
    		@if (Auth::check() && Auth::user()->id == $comment->user->id)
        		{!! Form::open(['url' => 'deletecomment']) !!}
        		{!! Form::hidden('commentId', $comment['id'], ['class' => 'form-control']) !!}
        		{!! Form::hidden('postId', $post['id'], ['class' => 'form-control']) !!}
           		 <button class="btn btn-success" type="submit">delete</button>
        		{!! Form::close() !!}
    		@endif
    		@if (Auth::check() && Auth::user()->id == $comment->user->id)
        		{!! Form::open(['url' => 'comment']) !!}
        		{!! Form::hidden('commentId', $comment['id'], ['class' => 'form-control']) !!}
        		{!! Form::hidden('postId', $post['id'], ['class' => 'form-control']) !!}
           		 <button class="btn btn-success" type="submit">edit</button>
        		{!! Form::close() !!}
    		@endif
    		</p>

    	@endforeach
    	</div>
    @endif
   <div class="row">
    	 {!! Form::open(['url' => 'createcomment']) !!}

        <div class="input-field">
            {!! Form::label('comment', 'Say something...') !!}
            {!! Form::text('comment', null, ['class' => '']) !!}
            {!! Form::hidden('postId', $post['id'], ['class' => 'form-control']) !!}
        </div>
        <button class="btn btn-success" type="submit">Comment...</button>
        {!! Form::close() !!}
    </div>

</div>
@stop