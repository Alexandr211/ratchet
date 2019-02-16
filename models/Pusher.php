<?php
namespace app\models;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Ratchet\Wamp\Topic;
use yii\helpers\Html;

class Pusher implements WampServerInterface {
    /**
     * A lookup of all the topics clients have subscribed to
     */
        protected $subscribedTopics = array();
    
    public function __construct()
    {
        echo "Websocket Server started!\n";
     
    }
    
    public function onSubscribe(ConnectionInterface $conn, $topic) 
    {
        $this->subscribedTopics[$topic->getId()] = $topic;
        echo "\nNew subscribtion: {$conn->resourceId}\n";
    }
    
    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */    

    public function onBlogEntry($entry) 
    {
        $entryData = json_decode($entry, true);
        //    echo $entryData['title'];
        
        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData['category'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['category']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }
    
    public function onClose(ConnectionInterface $conn) 
    {
        // TODO: Implement onClose() method.
        $this->clients->detach($conn);
        echo "\nUser {$conn->resourceId} disconnected!\n";
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) 
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) 
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
        echo "Connection {$topic->getId()} closed";
    }
    public function onError(ConnectionInterface $conn, \Exception $e) 
    {
        // TODO: Implement onError() method.
        $conn->close();
        echo "\nConnection {$conn->resourceId} closed with errors!\n";
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
    }
}