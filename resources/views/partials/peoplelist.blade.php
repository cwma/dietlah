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
    <ul class="list people-list-item collection" style="position:relative">
        @foreach($threads as $inbox)
            @if(!is_null($inbox->thread))
                <li class="collection-item avatar">
                    <a href="{{route('message.read', ['id'=>$inbox->withUser->id])}}">
                    <img src="{{$inbox->withUser->profile_pic}}" alt="avatar" class="circle"/>
                    <span class="title truncate">{{$inbox->withUser->username}}</span>
                    <p class="title truncate">@if(auth()->user()->id == $inbox->thread->sender->id)
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
    <div class="row">
        <form id="newconvo" method="post" action="/rest/getuserid" novalidate="novalidate">
        <div class="center" style="margin-bottom:10px;">
        <div class="input-field col s12">
            <input name="username" id="username" type="text" value="">
            <label id="input-validate-label" for="username">Enter username</label>
        </div>
        <button class="btn waves-effect waves-ligh light-green lighten-1" id="newconvobutton" type="submit">
            <i class="material-icons left">mail</i>Find user
        </div>
        </form>
    </div>
</div>

<script>
    function toggleChat(){
        $('#people-list').hide();
        $("#chat-window").show();
    }
</script>
