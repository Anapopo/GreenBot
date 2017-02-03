<?php
namespace protocol\io;

use greenbot\utils\Curl;
use greenbot\element\Bot;
use protocol\Queue;

class CallbackQueryAnswer extends \Thread{

    private $querybox;

    public function __construct(){
        $this->querybox = Queue::getInstance()->getQuerybox();
    }
    
    public function run(){

        $curl = new Curl();

        while(!$this->shutdown)
        {
            if(count($this->querybox['out']) > 0)
            {
                $serialized = $this->querybox['out']->shift();
                $query = unserialize($serialized);

                $json = $curl->
                setProxy()->
                setUrl('https://api.telegram.org/bot'.Bot::TOKEN.'/answercallbackquery')->
                setPost($query)->
                exec();
            }
            usleep(500000);
        }
    }
}