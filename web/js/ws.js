if (!window.WebSocket) {
    window.alert("Your browser does not support WebSocket!");
}

// Websocket script ------------------------------------------------------
// This subscribes on the definite broadcast WS channel
    var conn = new ab.Session('ws://127.0.0.1:8080',
        function() {
// Frontend receives the real time data from backend here ----------------
            conn.subscribe('MainCategory', function(topic, data) {
                
               //    console.log('Test title: ' + data.title);
                
// Put the real time data from backend to view ---------------------------
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

// -----------------------------------------------------------------------