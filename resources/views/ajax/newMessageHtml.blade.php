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