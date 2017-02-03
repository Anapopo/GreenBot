<?php
namespace greenbot\message;
use greenbot\console\MainLogger;
use greenbot\element\User;
use greenbot\element\Group;

class MessageQueue extends \Threaded{

    private static $instance;
    //private $protocol;
    private $inbox;
    private $outbox;

    private $querybox;

    public function __construct(){
        self::$instance = $this;
        $this->inbox = new \Threaded;
        $this->outbox = new \Threaded;

        $this->querybox = new \Threaded;
    }

    public static function getInstance(){
        return self::$instance;
    }
    
    public function getMessage(){
        if(count($this->inbox) > 0){
            $msg = $this->inbox->shift();
            return (new Message())->receive($msg);
        }
        return false;
    }

    public function getQuery(){
        if(count($this->querybox) > 0){
            $query = $this->querybox->shift();
            return (new CallbackQuery())->receive($query);
        }
        return false;
    }

    public function answerQuery($query, $text) {
        $answer = [
            'callback_query_id'=>$query->getId(),
            'text'=>$text
        ];
        //$this->outbox[] = serialize($answer);
        
    }

    public function sendMessage($target, Message $msg) {
        $class = @end(explode('\\', debug_backtrace()[3]['class']));
        MainLogger::info("[Plugin $class] $msg");//监听发送信息
        $send = [
            'chat_id'=> $target->getId(),
            'text'  =>$msg->getText()
        ];
        if($msg instanceof ReplyMessage){
             $extra = [
                'reply_to_message_id'=>$msg->getReplyId(),
            ];
            $send = array_merge($send,$extra);
        }
        if($msg instanceof KeyboardReplyMessage){
            $extra = [
                'reply_markup'=>$msg->getKeyboard()
            ];
            $send = array_merge($send,$extra);
        }

        print_r($send);
        
        $this->outbox[] = serialize($send);
    }

    public function getOutbox(){
        return $this->outbox;
    }

    public function getInbox(){
        return $this->inbox;
    }

    public function getQuerybox(){
        return $this->querybox;
    }
}