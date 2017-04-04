<div class="people-list" id="people-list">
    <div class="search" style="text-align: center">
        <a href="{{url('/home')}}" style="font-size:16px; text-decoration:none; color: white;"><i class="fa fa-user"></i> {{auth()->user()->name}}</a>
    </div>
    <div class="mobile-toggle"><button onclick="toggleChat()">Toggle</button></div>
    <ul class="list people-list-item">
        @foreach($threads as $inbox)
            @if(!is_null($inbox->thread))
        <li class="clearfix">
            <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
            <img src="{{$inbox->withUser->profile_pic}}" alt="avatar" class="chat_pic"/>
            <div class="about">
                <div class="name">{{$inbox->withUser->username}}</div>
                <div class="status">
                    @if(auth()->user()->id == $inbox->thread->sender->id)
                        <span class="fa fa-reply"></span>
                    @endif
                    <span>{{substr($inbox->thread->message, 0, 20)}}</span>
                </div>
            </div>
            </a>
        </li>
            @endif
        @endforeach

    </ul>
</div>

<script>
    function toggleChat(){
        var people = document.getElementById("people-list");
        people.style.display = "none";
        var chat = document.getElementById("chat-window");
        chat.style.display = "block";
    }
</script>
