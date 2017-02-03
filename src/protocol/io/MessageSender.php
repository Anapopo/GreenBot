<?php
namespace protocol\io;

use protocol\Queue;

use greenbot\message\Message;
use greenbot\utils\Curl;
use greenbot\element\Bot;

use greenbot\console\MainLogger;

class MessageSender extends \Thread{

    private $messagebox;

    public function __construct(){
        $this->messagebox = Queue::getInstance()->getMessagebox();
    }

    public function run()
    {   
        $curl = new Curl();
        
        while(!$this->shutdown)
        {
            while(count($this->messagebox['out']) > 0)
            {   
                //$start = time();
                $serialized = $this->messagebox['out']->shift();
                $msg = unserialize($serialized);
                //$msg = ['chat_id'=>197247422,'text'=>233];
                $json = $curl->
                setProxy()->
                returnHeader(false)->
                setUrl('https://api.telegram.org/bot'.Bot::TOKEN.'/sendmessage')->
                setPost($msg)->
                exec();
                //print_r($json);
                //$last = time() - $start;
                //MainLogger::warning('发送信息耗时'.$last.'s');
            }
            usleep(500000);
        }
    }
    
}
