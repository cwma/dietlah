@extends('layouts.chat')

@section('content')
    <div class="chat-history">
        <ul id="talkMessages">

            @foreach($messages as $message)
                @if($message->sender->id == auth()->user()->id)
                    <li class="clearfix" id="message-{{$message->id}}">
                        <div class="message-data right">
                            <span class="message-data-name" >{{$message->sender->username}}</span> -
                            <span class="message-data-time" >{{$message->humans_time}} ago</span> &nbsp; &nbsp;
                            <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fa fa-close"></i></a>
                        </div>
                        <div class="message other-message right">
                            {{$message->message}}
                        </div>
                    </li>
                @else

                    <li id="message-{{$message->id}}">
                        <div class="message-data">
                            <span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Messag"><i class="fa fa-close" style="margin-right: 3px;"></i></a>{{$message->sender->name}}</span>
                            <span class="message-data-name" >{{$message->sender->username}}</span> -
                            <span class="message-data-time">{{$message->humans_time}} ago</span>
                        </div>
                        <div class="message my-message clearfix">
                            {{$message->message}}
                        </div>
                    </li>
                @endif
            @endforeach


        </ul>

    </div> <!-- end chat-history -->

@endsection
