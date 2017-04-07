<div class="people-list card card-panel" id="people-list">
    <div class="search" style="text-align: center">
        <a href="{{url('/home')}}" style="font-size:16px; text-decoration:none; color: white;"><i class="fa fa-user"></i> {{auth()->user()->name}}</a>
    </div>
    <div class="row">
        <div class="mobile-toggle center">
            <button class="btn waves-effect waves-ligh light-green lighten-1" onclick="toggleChat()">
                <i class="material-icons left">person</i>Hide Users
            </button>
        </div>
    </div>
    <ul class="list people-list-item collection">
        @foreach($threads as $inbox)
            @if(!is_null($inbox->thread))
                <li class="collection-item avatar">
                    <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
                    <img src="{{$inbox->withUser->profile_pic}}" alt="avatar" class="circle"/>
                    <span class="title">{{$inbox->withUser->username}}</span>
                    <p>@if(auth()->user()->id == $inbox->thread->sender->id)
                                <i class="material-icons right">reply</i>{{substr($inbox->thread->message, 0, 20)}}
                        @else
                        {{substr($inbox->thread->message, 0, 20)}}
                        @endif
                    </p>
                </a>
                </li>

            @endif
        @endforeach

    </ul>
</div>

<script>
    function toggleChat(){
        $('#people-list').hide();
        $("#chat-window").show();
    }
</script>
