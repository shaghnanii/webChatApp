@extends('layouts.app')

@section('content')

    <div class="container">
        <h3 class=" text-center">Real Time Messages</h3>
        <div class="messaging">
            <div class="inbox_msg">
                <div class="inbox_people">
                    <div class="headind_srch">
                        <div class="recent_heading">
                            <h4>Recent Chats</h4>
                        </div>
                    </div>

                    {{-- conversation list starts here --}}
                    <div class="inbox_chat">

                        @if (count($conversations) > 0)

                            @foreach ($conversations as $item)
                                <div class="chat_list active_chat chat_style" id="chat_head" onclick="showThisChat('test')">
                                    <div class="chat_people">
                                        <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png"
                                                alt="sunil"> </div>
                                        <div class="chat_ib">
                                            <h5>{{ $item->u_two->name }}
                                                <span class="chat_date">
                                                    <span class="dot">0</span>
                                                </span>
                                            </h5>
                                            <p>{{ $item->u_two->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="chat_list active_chat">
                                <div class="alert alert-warning">No Conversations Found</div>
                            </div>
                        @endif
                    </div>
                    {{-- conversation lists end here --}}
                </div>
                <div class="mesgs">
                    <div class="msg_history">
                        @if (count($conversations) > 0)
                            {{-- message chats sections here --}}
                            @foreach ($conversations as $conversation)
                            <div id="msg_body_section">
                                @if($loop->first)
                                @foreach ($conversation->messages as $msg)
                                        @if ($msg->message_from != Auth::user()->id)
                                            {{-- message from other person starts here --}}
                                            <div class="incoming_msg" id="recieved_message">
                                                <div class="incoming_msg_img"> <img
                                                        src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
                                                </div>
                                                <div class="received_msg">
                                                    <div class="received_withd_msg">
                                                        <p> {{ $msg->message }} </p>
                                                        <span class="time_date"> 11:01 AM | June 9</span>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- msg from other person ends here --}}
                                        @else
                                            {{-- msg by me starts here --}}
                                            <div class="outgoing_msg">
                                                <div class="sent_msg">
                                                    <p>{{ $msg->message }}</p>
                                                    <span class="time_date"> 11:01 AM | June 9</span>
                                                </div>
                                            </div>
                                            {{-- msg by me ends here --}}
                                        @endif
                                @endforeach
                                @endif
                                        </div>
                            @endforeach
                            {{-- message chats section ends here --}}
                        @endif
                    </div>

                    {{-- msg send/write section start here --}}
                    <div class="type_msg">
                        <div class="input_msg_write">
                            <form id="someid" method="POST">
                                <input type="text" id="message" placeholder="Write a message...">
                                <input type="hidden" id="mID" name="mID" value="{{ Auth::user()->id }}" />
                                <input type="hidden" id="cID" name="cID" value="{{ $item->id }}" />
                                <button type="submit" class="msg_send_btn">
                                    <i class="fa fa-paper-plane-o"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    {{-- msg send/write section ends here --}}
                </div>

            </div>
        </div>
    </div>


    <script>
        Echo.private('privateChatChannel')
            .listen('TestEvent', (e) => {
                var array = [];
                console.log("Fetching messages");
                console.log(e.message.message);
                console.log(e.message.message_from);
                array.push(e.message.message);
                var content = document.getElementById("recieved_message");
                var checkUser = e.message.message_from;

                var mID = "{{ Auth::user()->id }}";

                for (var i = 0; i < array.length; i++) {
                    if (checkUser != mID) {
                        content.innerHTML +=
                            ' <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>' +
                            ' <div class="received_msg">' +
                            ' <div class="received_withd_msg">' +
                            ' <p> ' + array[i] + ' </p>' +
                            ' <span class="time_date"> 11:01 AM    |    June 9</span>' +
                            ' </div>' +
                            ' </div>';
                    }

                }
            });

    </script>

    <script>
        $(document).ready(function(e) {
            $("#someid").submit(function(event) {
                event.preventDefault();
                // return false;
                $.ajax({
                    url: "chats",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        message: $("#message").val(),
                        mID: $("#mID").val(),
                        cID: $("#cID").val(),
                    },
                    // cache: false,
                    dataType: 'json',
                    success: function(dataResult) {
                        console.log(dataResult);
                    }
                });
            });

        });

    </script>

    <script>
        function showThisChat(data) {
            alert(data);
        }

    </script>
@endsection
