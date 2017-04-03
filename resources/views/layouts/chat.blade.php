@extends('template')

@section('title', 'DietLah!')

@section('page-content')
    <div class="container clearfix body chat-container">
        @include('partials.peoplelist')

        <div class="chat" id="chat-window">
            <div class="chat-header clearfix">
                @if(isset($user))
                    <img src="{{@$user->profile_pic}}" alt="avatar" class="active_chat_pic"/>
                @endif
                <div class="chat-about">
                    @if(isset($user))
                        <div class="chat-with">{{'Chat with ' . @$user->username}}</div>
                    @else
                        <div class="chat-with">No Thread Selected</div>
                    @endif
                </div>
                <i class="fa fa-star"></i>
                <div class="mobile-toggle"><button onclick="togglePeople()">Toggle</button></div>
            </div> <!-- end chat-header -->

            @yield('content')

            <div class="chat-message clearfix">
                <form action="" method="post" id="talkSendMessage">
                <textarea name="message-data" id="message-data" placeholder ="Type your message" rows="3"></textarea>
                <input type="hidden" name="_id" value="{{@request()->route('id')}}">
                <button type="submit btn btn-success pull-right">Send</button>
            </form>

            </div> <!-- end chat-message -->

        </div> <!-- end chat -->

    </div> <!-- end container -->

    <script>
        function togglePeople(){
            var chat = document.getElementById("chat-window");
            chat.style.display = "none";
            var people = document.getElementById("people-list");
            people.style.display = "block";
        }
    </script>

    <script>
        var __baseUrl = "{{url('/')}}"
    </script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js'></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js'></script>

    <script src="{{asset('chat/js/talk.js')}}"></script>

    <script>
        var show = function(data) {
            alert(data.sender.name + " - '" + data.message + "'");
        }

        var msgshow = function(data) {
            var html = '<li id="message-' + data.id + '">' +
            '<div class="message-data">' +
            '<span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="' + data.id + '" title="Delete Messag"><i class="fa fa-close" style="margin-right: 3px;"></i></a>' + data.sender.name + '</span>' +
            '<span class="message-data-time">1 Second ago</span>' +
            '</div>' +
            '<div class="message my-message">' +
            data.message +
            '</div>' +
            '</li>';

            $('#talkMessages').append(html);
        }

    </script>
    {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['msgshow']]]) !!}

@stop
