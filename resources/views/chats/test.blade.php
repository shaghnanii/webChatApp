@if (count($conversations) > 0)
    {{-- message chats sections here --}}
    @foreach ($conversations as $conversation)
        <div id="msg_body_section">
            @if ($loop->first)
                @foreach ($conversation->messages as $msg)
                    @if ($msg->message_from != Auth::user()->id)
                        {{-- message from other person starts here --}}
                        <div class="incoming_msg" id="recieved_message">
                            <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png"
                                    alt="Test User">
                            </div>
                            <div class="received_msg">
                                <div class="received_withd_msg">
                                    <p> {{ $msg->message }} </p>
                                    <span
                                        class="time_date">{{ date('d M, H:i A', strtotime($msg->updated_at)) }}</span>
                                </div>
                            </div>
                        </div>
                        {{-- msg from other person ends here --}}
                    @else
                        {{-- msg by me starts here --}}
                        <div class="outgoing_msg">
                            <div class="sent_msg">
                                <p>{{ $msg->message }}</p>
                                <span class="time_date">
                                    {{ date('d M, H:i A', strtotime($msg->updated_at)) }} |
                                    <span style="color: black">{{ $msg->is_seen ? 'Seen' : 'Delivered' }}</span>
                                </span>
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
