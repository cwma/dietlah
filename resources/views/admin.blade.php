@extends('template')

@section('title', "Report handling")

@section('page-content')
<div class="container">

    <div class="row">
        <h3>Reports</h3>
    </div>

    <div class="row">
        <!--for each report here-->
            <table class="table">
                <tr>
                    <td>
                       <p>Report type</p>
                    </td>
                    <td>
                        <p>Report source</p>
                    </td>
                    <td>
                        <p>Report User</p>
                    </td>
                    <td>
                        <p>report text</p>
                    </td>
                </tr>
                @foreach($reports as $report)
                <tr>
                    <td>
                        <p>{{$report['report_type']}}</p>
                    </td>
                    <td>
                        @if($report['report_type'] == 'post')
                            <p><a href="/post/{{$report['reported_id']}}">link to post</a></p>
                        @else
                            {{$comment = App\Comment::find($report['reported_id'])}}
                            <p><a href="/post/{{$comment->post->id}}">link to post</a></p>
                        @endif

                    </td>
                    <td>
                        <p><a href="/profile/{{$report['user_id']}}">link to profile</a></p>
                    </td>
                    <td>
                        <p>{{$report['report_comment']}}</p>
                    </td>
                </tr>
                @endforeach
            </table>
        <!--end for each-->
    </div>

</div>
@stop
