<div class="chat">

  <!-- Header -->
  <div class="top">
 
    <div>
      <p>Ross Edlin </p>
      <small>Online</small>
    </div>
  </div>
  <!-- End Header -->

  <!-- Chat -->

  <div class="messages">
    @foreach($conversations as $conversation)
    @include('admin-views.chat.receive', ['message' => $conversation['message']])
    @endforeach

    <!-- @include('admin-views.chat.receive', ['message' => "Ask a friend to open this link and you can chat with them!"]) -->
  </div>
  <!-- End Chat -->

  <!-- Footer -->
  <div class="bottom">
    <form>
      <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
      <input type="hidden" id="user_id" name="user_id" value="{{$user_id}}">
      <button type="submit">Send Message</button>
    </form>
  </div>
  <!-- End Footer -->

</div>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
  const pusher  = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster: 'mt1'});
   const channel = pusher.subscribe('public');
 
  //  const conversation_id = 27;
  // const channel = pusher.subscribe('conversations.27');

  //Receive messages
  channel.bind('chat', function (data) {
    $.post("{{route('admin.message.receive')}}", {
      _token:  '{{csrf_token()}}',
      message: data.message,
    })
     .done(function (res) {
       $(".messages > .message").last().after(res);
       $(document).scrollTop($(document).height());
     });
  });

  //Broadcast messages
  $("form").submit(function (event) {
    event.preventDefault();

    $.ajax({
      url:  "{{route('admin.message.broadcast')}}",
      method:  'POST',
      headers: {
        'X-Socket-Id': pusher.connection.socket_id
      },
      data:    {
        _token:  '{{csrf_token()}}',
        message: $("form #message").val(),
        user_id: $("form #user_id").val(),
      }
    }).done(function (res) {
      $(".messages > .message").last().after(res);
      $("form #message").val('');
      $(document).scrollTop($(document).height());
    });
  });

</script>

