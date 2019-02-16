Установка логики Websocket Real Time обновления информации на стороне клиента по инициативе Backend  -------------------------------------------------------------------------------------------------

1. Данное решение предназначено для оптимизации работы веб приложений, которые имеют необходимость в постоянном режиме обновлять на стороне клиента информацию из баз данных и из других источников на бэкенд в зависимости от различных событий.  
Пример реализации: 
1.1. первоначальная загрузка/обновление страницы - информация подгружается в виджеты по разовым ajax запросам! 
1.2. real time информация поступает в виджеты через Websocket без периодических ajax запросов на бэкенд со стороны фронтенд.

Пример установки на Yii2:
2. Установка через Composer на сервер в приложение библиотеки php Ratchet (www.socketo.me)
3. Установка ZeroMQ library (libzmq) as well as a PECL extension for PHP bindings.
4. Установка React/ZMQ в приложение
5. Ядром логики являются:
5.1. PushserverController.php - It's the engine of WS server! Необходимо произвести настройку tcp (по умолчанию на localhost)!
5.2. Pusher.php - бэкенд модель которая регистрирует клиентов-пользователей в зависимости от их категорий и осуществляет WebSocket broadcast рассылку клиентам соответствующей real time информации от Бэкенд.
5.3. Протокол ZMQ транспорта от PHP to WS server. Логика представлена в файле WebhookparseController.php, но может быть реализована в любом соответствующем методе на Бэкенд.
Необходимо произвести настройку tcp!
Пример реализации протокола ниже.
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
5.4. Протокол регистрации клиентов на Websocket server при подключении пользователей, установки им соответствующей категории и получения ими real time информации в виде массива данных реализован в файле ws.js, который должен быть зарегистрирован в соответствующем View. При этом для корректной работы скрипта необходимо подключить в соответствующий view библиотеку autobahn.min.js
Также необходимо произвести настройку ws!
Пример реализации ниже.
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
6. Рекомендуемый демон для поднятия WS сервера - Supervisor.
7. Рекомендуемая серверная конфигурация - http://socketo.me/docs/deploy
8. Максимальный лимит одновременных открытых соединений. Дефолтное значение - 1024. Увеличение лимита - http://socketo.me/docs/deploy