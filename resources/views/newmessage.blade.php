@extends('template')

@section('title', 'DietLah!')

@section('page-content')

<div class="container">

    <div> <!-- use this to style size later -->
        <div class="row">
            <h3>All Users</h3>
        </div>

        <div class="row">
            @foreach ($users as $user)
                <table class="table">
                    <tr>
                        <td>
                            <img src="{{$user->profile_pic}}">
                            {{$user->username}}
                        </td>
                        <td>
                            <a href="./newmessage">Send Message</a>
                        </td>
                    </tr>
                </table>
            @endforeach
        </div>
    </div>

</div>
@stop
