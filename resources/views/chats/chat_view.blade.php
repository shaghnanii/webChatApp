@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Chats') }}</div>

                    <div class="card-body">

                        @foreach ($conversations as $item)
                            {{ $item->u_two->name }}
                            {{ $item->id }}



                            <form id="someid" method="POST">
                                <input type="text" id="message">
                                <input type="hidden" id="mID" name="mID" value="{{ Auth::user()->id }}" />
                                <input type="hidden" id="fID" name="fID" value="{{ $item->u_two->id }}" />
                                <input type="hidden" id="cID" name="cID" value="{{ $item->id }}" />
                                <button type="submit">submit</button>
                            </form>

                            <div id="sent_message"></div>

                        @endforeach

                    </div>
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
                console.log(e.message.message_to);
                array.push(e.message.message);
                var content = document.getElementById("sent_message");
                var checkUser = e.message.message_to;

                var mID = "{{ Auth::user()->id }}";

                for (var i = 0; i < array.length; i++) {
                    // if (checkUser != mID) {
                    content.innerHTML +=
                        '<div class="alert alert-success">' +
                        array[i] +
                        ' </div>';
                    // }/

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
                        fID: $("#fID").val(),
                        cID: $("#cID").val(),
                    },
                    // cache: false,
                    dataType: 'json',
                    success: function(dataResult) {
                        console.log(dataResult);
                        // do add the sent message to the body here 
                    },
                    before: function() {
                        console.log("beforer");
                    }
                });
            });

        });

    </script>
@endsection
