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
    
</div>
@stop