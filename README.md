<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### Laravel Web Sockets Installation Steps

```
composer require beyondcode/laravel-websockets
```

This package comes with a migration to store statistic information while running your WebSocket server. You can publish the migration file using:
```
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
```

Run the migrations with:
```
php artisan migrate
```

Next, you need to publish the WebSocket configuration file:
```
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
```

Set the env file pusher credintails.
```
PUSHER_APP_ID=yourAppId
PUSHER_APP_KEY=yourKeyId
PUSHER_APP_SECRET=yourSecretId
PUSHER_APP_CLUSTER=yourCluster
```

Now we will install pusher package through composer.
```
composer require pusher/pusher-php-server "~3.0"
```

Don't forget to change the BROADCAST_DRIVER in the env file.
```
BROADCAST_DRIVER=pusher
```

Pusher Configuration.
Now we will set the pusher configuration in the config/broadcasting.php
```
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true,
        //'host' => '127.0.0.1',
        //'port' => 6001,
        //'scheme' => 'http'
    ],
],
```

Setup Laravel Echo.
First we will install laravel-echo and pusher-js library using npm.
```
npm install laravel-echo pusher-js
or 
npm install --save laravel-echo pusher-js
```

Uncomment the laravel echo from the bootstrap (resources/js/bootstrap.js).
Add these lines to the uncommented section.
```
    
import Echo from "laravel-echo"

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'xxxxxxxxxxxxxxxxxxxx',
    cluster: 'eu',
    encrypted: true
});

```

Now create two more Models, Conversation and Message with migration table.
```
php artisan make:model Conversation -m
php artisan make:model Message -m
```

Do more work on the migration table, like add the require fields both in conversation and message migration table and migrate it.
Also establish the relationship between User, Conversation and Message Model. 

Now test your application if everything is going on the right side.
Open terminal and run this command.
```
php artisan serve
```

Open another terminal and run this command to start the websocket serve.
```
php artisan websockets:serve
```
To watch the changes in the js file run the npm watch command on another terminal.
```
npm run watch
```

Now Create an event using artisan. Note: Dont forget to implement the event with ShouldBroadcast.
```
php artisan make:event SomeEvent
```
Do the implement like this.
```
class SomeEvent implements ShouldBroadcast {
    // your class body here...
}
```

To listen for an event do this in your js part.
```
Window.Echo.channel('privateChatChannel')
.listen('Chat/ChatEvent', (e) => {
    console.log(e);
});
```

To call for the event use the broadcast function in your calling method whatever you are using.
```
broadcast(new SomeEvent('some kind of message here'));
```

Dont forget to uncomment the BroadcastServiceProvider from config/app.js.
```
App\Providers\BroadcastServiceProvider::class,
```

Remove differ from the script tag in your app.blade.php head tag.
```
<script src="{{ asset('js/app.js') }}"></script>
```

And add the snippit into your page where you want to listen to the broadcast.

```
    <script>
        window.Echo.channel('privateChatChannel')
            .listen('ChatEvent', (e) => {
                console.log(e);
            });

    </script>
```

Test your proejct by hitting the url where you are broadcasting the event. You will get some kind of message realated to your event in the 127.0.0.1:8000/laravel-websockets tab.

We are using basically these routes for our applicaiton.
```
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('chats', [ChatsController::class, 'showChats']);
Route::post('chats', [ChatsController::class, 'sendChat']);

Route::post('getMyChat', [ChatsController::class, 'getUserChat']);vv
```

The first route will hit first time when the chat page is loaded and will return with some data like, last msg, unread message count and user data.
In the next step when we click/select a chat we will use the following javascript funciton to update ui according to our conversation selection.
```
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
```

On we click on a conversation we are send a conversation id to load and process more work on that particular conversation only. This funciton accept the id and process data accordingly.

The below script is used to send the message through ajax request.
```
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
```
This script pull the message input filed data and send it over to db and broadcast in background. This function also trigger the previous showThisChat(ID), with the particualr conversation id so that the UI will be updated accordingly.

When a message is broadcasted over the sockets the reciever funciton recieve this broadcast and update the ui by calling the same showThisChat(ID) method by providing the conversation id as a parameter to that funciton.


Files to be considered when working on this project.
> Important Files
```
-   app
    -   Events
        -   TestEvent.php
    -   Http
        -   Controllers
            -   Chat
                -   ChatsController.php
            -   HomeController.php
    -   Models
        -   Chat
            -   Conversation.php
            -   Message.php
        -   User.php
-   config
    -   app.php
    -   broadcasting.php
-   resources
    -   js
        -   bootstrap.js
    -   views
        -   chat_view.blade.php
-   routes
    -   web.php
```