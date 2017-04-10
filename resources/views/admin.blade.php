@extends('template')

@section('title', "Report handling")

@section('page-content')
<div class="container">

    <div class="row">
        <h3>Reports</h3>
    </div>

    <div class="row">
        <ul class="collection">
            @foreach($reports as $report)
                <li class="collection-item">
                    <span class="title"><b>Report Type</b>: {{$report['report_type']}}</span>
                    @if($report['report_type'] == 'post')
                        @if ($posts[$report['reported_id']])
                            <p><b>Post Title</b>: <a href="/post/{{$posts[$report['reported_id']]->id}}">{{$posts[$report['reported_id']]->title}}</a></p>
                            <p><b>Summary</b>: {{$posts[$report['reported_id']]->summary}}</p>
                            <p><b>Report Comment</b>: {{$report['report_comment']}}</p>
                        @else
                            <p><b>Post has been deleted</b></p>
                            <p><b>Report Comment</b>: {{$report['report_comment']}}</p>
                        @endif
                    @elseif($report['report_type'] == 'comment')
                        @if ($posts[$report['reported_id']])
                            <p><b>Post Title</b>: {{$posts[$report['reported_id']]->summary}}<a href="/post/{{$posts[$report['reported_id']]->post->id}}">{{$posts[$report['reported_id']]->post->title}}</a></p>
                            <p><b>Comment</b>:{{$posts[$report['reported_id']]->comment}}</p>
                            <p><b>Report Comment</b>: {{$report['report_comment']}}</p>
                        @else
                            <p><b>Comment has been deleted</b></p>
                            <p><b>Report Comment</b>: {{$report['report_comment']}}</p>
                        @endif
                    @endif
                    <p><a href="/profile/{{$report['user_id']}}">Reporter</a></p>
                </li>
            @endforeach
        </ul>

        {{ $reports->links() }}

    </div>

</div>
@stop
