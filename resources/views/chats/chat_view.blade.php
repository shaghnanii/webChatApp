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

                        <?php $msg_count = 0; ?>
                        @if (count($conversations) > 0)
                            @foreach ($conversations as $item)
                                <div class="chat_list active_chat chat_style" id="chat_head"
                                    onclick="showThisChat({{ $item->id }})">
                                    <div class="chat_people">
                                        <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png"
                                                alt="User"> </div>
                                        <div class="chat_ib">
                                            <h5>
                                                @if ($item->u_two->name != Auth::user()->name)
                                                    {{ $item->u_two->name }}
                                                @else
                                                    {{ $item->u_one->name }}
                                                @endif
                                                <span class="chat_date">
                                                    <span class="dot" id="countMsg">{{ $item->unread_msg_count }}</span>
                                                </span>
                                            </h5>
                                            <p>
                                                @foreach ($item->messages as $last)
                                                    @if ($loop->last)
                                                        {{ $last->message }}
                                                    @endif
                                                @endforeach
                                            </p>
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

                        {{-- message chats sections here --}}
                        <div id="msg_body_section">


                        </div>

                        {{-- message chats section ends here --}}

                    </div>

                    {{-- msg send/write section start here --}}
                    <div class="type_msg">
                        <div class="input_msg_write">
                            <form id="someid" method="POST">
                                <input type="text" id="message" placeholder="Write a message...">
                                <input type="hidden" id="mID" name="mID" value="{{ Auth::user()->id }}" />
                                <input type="hidden" id="cID" name="cID" value="" />
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
        Echo
            .private('privateChatChannel')
            .listen('TestEvent', (e) => {
                var array = [];
                // console.log("Fetching messages");
                console.log("Message: " + e.message.message);
                console.log("Message From: " + e.message.message_from);
                array.push(e.message.message);
                var content = document.getElementById("recieved_message");
                var checkUser = e.message.message_from;

                var mID = "{{ Auth::user()->id }}";

                showThisChat(e.message.conversation_id);
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
                        var tempID = document.getElementById("cID").value;
                        showThisChat(tempID);
                    }
                });
            });

        });

    </script>

    <script>
        function showThisChat(conversation_id) {
            console.log("resetting chat body for conversation id : " + conversation_id);
            // settting input filed send message values
            document.getElementById("cID").value = conversation_id;


            var getUpdatedData = '';
            $.ajax({
                url: "getMyChat",
                type: "POST",
                // async: false,
                // cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    convID: conversation_id
                },
                dataType: 'json',
                success: function(dataResult) {
                    getUpdatedData = dataResult;
                    var msgBody = document.getElementById('msg_body_section');

                    var msgTempBody = "";

                    // console.log(messages)
                    var temp_auth_id = {{ Auth::user()->id }};

                    // console.log(typeof(temp_auth_id))

                    for (var i = 0; i < getUpdatedData.messages.length; i++) {
                        // console.log("Auth ID: " + temp_auth_id);
                        // console.log("From ID: " + messages[i].message_from);
                        const d = new Date(getUpdatedData.messages[i].updated_at);
                        const ye = new Intl.DateTimeFormat('en', {
                            year: 'numeric'
                        }).format(d);
                        const mo = new Intl.DateTimeFormat('en', {
                            month: 'short'
                        }).format(d);
                        const da = new Intl.DateTimeFormat('en', {
                            day: '2-digit'
                        }).format(d);

                        if (getUpdatedData.messages[i].message_from != temp_auth_id) {
                            msgTempBody += '<div class="incoming_msg" id="recieved_message"> ' +
                                '    <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" ' +
                                '            alt="User"> ' +
                                '    </div> ' +
                                '    <div class="received_msg"> ' +
                                '        <div class="received_withd_msg"> ' +
                                '            <p>' + getUpdatedData.messages[i].message + '</p> ' +
                                '            <span ' +
                                '                class="time_date"> ' + `${da} ${mo}, ${ye}` + '  </span> ' +
                                '        </div> ' +
                                '    </div> ' +
                                '</div> ';
                        } else {
                            msgTempBody += '<div class="outgoing_msg"> ' +
                                '    <div class="sent_msg"> ' +
                                '        <p>' + getUpdatedData.messages[i].message + '</p> ' +
                                '        <span class="time_date"> ' +
                                '            ' + `${da} ${mo}, ${ye}` + ' | ' +
                                '            <span style="color: black"> ' + getUpdatedData.messages[i]
                                .is_seen + ' </span> ' +
                                '        </span> ' +
                                '    </div> ' +
                                '</div> ';
                        }
                    }
                    msgBody.innerHTML = msgTempBody;
                }
            });



        }

    </script>
@endsection
