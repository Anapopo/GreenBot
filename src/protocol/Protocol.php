<?php
namespace protocol;

use greenbot\console\MainLogger;
use greenbot\element\User;

use protocol\io\UpdateReceiver;
use protocol\io\MessageSender;
use protocol\io\CallbackQueryAnswer;

class Protocol{

    private static $instance;
    private $error;

    private $receiver;

    private $msgsender;
    private $queryanswer;


    public function __construct(){
        self::$instance = $this;
    }

    public function run()
    {
        //MainLogger::info('GreenBot 消息/查询队列正在初始化中...');
        $this->receiver = new UpdateReceiver($this);

        $this->msgsender = new MessageSender($this);
        $this->queryanswer = new CallbackQueryAnswer($this);

        $this->receiver->start();
        $this->msgsender->start();
        $this->queryanswer->start();
        //MainLogger::success('GreenBot的消息/查询队列初始化成功！');
    }

    public function shutdown(){
        $this->sender->shutdown();
        $this->receiver->shutdown();
    }

    public function getMessageSender(){
        return $this->sender;
    }

    public function getMessageReceiver(){
        return $this->receiver;
    }

    public static function getInstance(){
        return self::$instance;
    }

    public function isError(){
        return $this->error;
    }

}