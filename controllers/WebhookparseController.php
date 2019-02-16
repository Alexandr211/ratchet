<?php
namespace app\controllers;
use Yii;

class WebhookparseController extends \yii\web\Controller
{
    public function actionAmoleads()
	{
        // Any code you needs can be here ---------------------------------------------------
        
        // ZMQ transport Data in array to Websocket server ------------------------
                        
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
        }      

	}
}

