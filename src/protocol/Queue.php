<?php
namespace protocol;

use greenbot\message\Message;
use greenbot\message\ReplyMessage;
use greenbot\message\KeyboardReplyMessage;

use greenbot\element\Chat;
use greenbot\element\Photo;
use greenbot\element\Sticker;

use greenbot\query\CallbackQuery;
use greenbot\console\MainLogger;

class Queue extends \Threaded{

    private static $instance;

    private $messagebox;

    private $querybox;

    public function __construct()
    {
        self::$instance = $this;
        $this->messagebox = new \Threaded;
        $this->querybox = new \Threaded;

        $this->messagebox['in'] = new \Threaded;
        $this->messagebox['out'] = new \Threaded;

        $this->querybox['in'] = new \Threaded;
        $this->querybox['out'] = new \Threaded;
    }

    public static function getInstance(){
        return self::$instance;
    }

    public function getMessage()
    {   
        if(count($this->messagebox['in']) > 0){
            $msg = $this->messagebox['in']->shift();
            return (new Message())->receive($msg);
        }
        return false;
    }

    public function getQuery()
    {
        if(count($this->querybox['in']) > 0){
            $query = $this->querybox['in']->shift();
            return (new CallbackQuery())->receive($query);
        }
        return false;
    }

    public function answerQuery($query, $text, $alert = false)
    {
        $answer = [
            'callback_query_id'=>$query->getId(),
            'show_alert'=>$alert,
            'text'=>$text
        ];
        $this->querybox['out'][] = serialize($answer);
    }

    public function sendMessage(Chat $target, Message $msg, $parse_mode = 'Markdown')
    {
        //$class = @end(explode('\\', debug_backtrace()[3]['class']));
        //MainLogger::info("[Plugin $class] $msg");//监听发送信息
        //var_dump(debug_backtrace());
        //print_r($target);
        $send = [
            'chat_id'   =>$target->getId(),
            'text'      =>$msg->getText(),
            'parse_mode'=>$parse_mode
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

        //print_r($send);
        
        $this->messagebox['out'][] = serialize($send);
        MainLogger::info('[Me->'.$target->getName().'] '.$msg->getText());
    }

    public function getMessagebox(){
        return $this->messagebox;
    }

    public function getQuerybox(){
        return $this->querybox;
    }



}