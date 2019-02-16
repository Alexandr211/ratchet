# Fast implementation of Real Time frontend data updates by Backend pushes to Websockets server (Ratchet php lib)
-----------------------------------------------------------------------------------------------

1. This solution is designed to optimize the performance of web applications which have the need of real time updates the client-side information from databases and other sources on the backend, depending on various events.
The next logic can be implemented.
1.1. initial loading / updating of the page - information is loaded into widgets for one-time ajax requests!
1.2. real time information is put to the widgets via Websocket without periodic ajax requests to the backend from the frontend.

The example Yii2 installation:
2. Installation via Composer the php library - Ratchet (www.socketo.me) in the app.
3. Install the ZeroMQ library (libzmq) and the PECL extension for PHP Bindings.
4. Install React/ZMQ in the app.
5. The core of logic are:
5.1. PushserverController.php - It's the engine of WS server! You must configure tcp (default to localhost)!
5.2. Pusher.php-backend model that registers customers-users depending on their categories and provides WebSocket broadcast real time information to the customers from the Backend.
5.3. ZMQ Transport Protocol from PHP to WS server. The logic is represented in the WebhookparseController.php, but can be implemented in any appropriate method on the Backend.
You must configure tcp also!
An example of the Protocol implementation is below.
// ZMQ transport Data in array to Websocket server -------------------------
                        
        $entryData = array(
        'category' => 'MainCategory',
        'param1' => $param1,
        'param2' => $param2        
    );
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://127.0.0.1:5555");
        $socket->send(json_encode($entryData));
            
        // ------------------------------------------------------------------------
        
5.4. The Protocol of the clients registration by Websocket server and receiving by them real time information in the form of an array of data is realized in the ws.js that must be registered in the appropriate View. In this case, use autobahn.min.js in the view for the correct operation of the script. 
You also need to configure ws address!        
An example is below.
//---------------------------------------------------------------------------------
if (!window.WebSocket) {
    window.alert("Your browser does not support WebSocket!");
}

// Websocket script ---------------------------------------------------------------
// This subscribes on the definite broadcast WS channel
    var conn = new ab.Session('ws://127.0.0.1:8080',
        function() {
// Frontend receives the real time data from backend here -------------------------
            conn.subscribe('MainCategory', function(topic, data) {
                
               //    console.log('Test title: ' + data.title);
                
// Put the real time data from backend to view ------------------------------------
    var messageContainer1 = document.createElement('div');
    var sumNode = document.createTextNode(data.sum_leads142);
    messageContainer1.appendChild(sumNode);
    document.getElementById("sum_leads")
    .appendChild(messageContainer1); 
                
    var messageContainer2 = document.createElement('div');
    var countNode = document.createTextNode(data.count_leads142);
    messageContainer2.appendChild(countNode);
    document.getElementById("count_leads")
    .appendChild(messageContainer2); 
    
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );

// --------------------------------------------------------------------------------

6. Use Supervisor to run your WS server.
7. Recommended server configuration - http://socketo.me/docs/deploy
8. Maximum limit of the open connections is 1024 by default. To increase the limit - http://socketo.me/docs/deploy
