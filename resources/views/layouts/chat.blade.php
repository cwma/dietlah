@extends('template')

@section('title', 'DietLah!')

@section('page-content')
    <div class="container chat-container">
        @include('partials.peoplelist')

        <div class="chat card-panel" id="chat-window">
            <div class="mobile-toggle center">
                <button class="btn waves-effect waves-ligh light-green lighten-1" onclick="togglePeople()">
                    <i class="material-icons left">person</i>Show Users
                </button>
            </div>
            <ul class="list people-list-item collection">
                <li class="collection-item avatar">
                    @if(isset($user))
                        <img src="{{@$user->profile_pic}}" alt="avatar" class="circle"/>
                        <span class="title">{{'Messages from ' . @$user->username}}</span>
                    @else
                        <span class="title">No Thread Selected</span>
                    @endif
                </li>
            </ul> <!-- end chat-header -->

            <div class="chat-history">
                <ul id="talkMessages">

                    @foreach($messages as $message)
                        @if($message->sender->id == auth()->user()->id)
                            <li class="clearfix right-align" id="message-{{$message->id}}">
                                <div class="message-data align-right">
                                    <span class="message-data-name" >{{$message->sender->username}}</span> -
                                    <span class="message-data-time" >{{$message->humans_time}} ago</span> &nbsp; &nbsp;
                                    <a href="" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="material-icons right">close</i></a>
                                </div>
                                <div class="message other-message light-green lighten-3">
                                    {{$message->message}}
                                </div>
                            </li>
                        @else

                            <li class="left-align" id="message-{{$message->id}}">
                                <div class="message-data">
                                    <span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Messag"><i class="fa fa-close" style="margin-right: 3px;"></i></a>{{$message->sender->name}}</span>
                                    <span class="message-data-name" >{{$message->sender->username}}</span> -
                                    <span class="message-data-time">{{$message->humans_time}} ago</span>
                                </div>
                                <div class="message my-message light-green lighten-5">
                                    {{$message->message}}
                                </div>
                            </li>
                        @endif
                    @endforeach


                </ul>

            </div> <!-- end chat-history -->

            @if(isset($user))
            <div class="chat-message">
                <form action="" method="post" id="talkSendMessage">
                    <div class="input-field col s12">
                        <textarea name="message-data" id="message-data" class="materialize-textarea" placeholder="type your message" row="3"></textarea>
                        <input type="hidden" name="_id" value="{{@request()->route('id')}}">
                        <button class="btn waves-effect waves-ligh light-green lighten-1 submit">
                            <i class="material-icons left">send</i>send message
                        </button>
                    </div>
                </form>
            </div> <!-- end chat-message -->
            @endif

        </div> <!-- end chat -->

    </div> <!-- end container -->

    <script>
        function togglePeople(){
            $('#people-list').show();
            $("#chat-window").hide();
        }
    </script>

    <script>
        var __baseUrl = "{{url('/')}}"
    </script>
@stop

@section('scripts')
    <script src='http://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js'></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
    <script type="text/javascript" src="js/typeahead.bundle.min.js"></script>
    <script src="{{asset('chat/js/talk.1.js')}}"></script>
@stop
