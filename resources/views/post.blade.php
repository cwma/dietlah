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
    
</div>
@stop