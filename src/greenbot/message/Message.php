<?php
namespace greenbot\message;

use greenbot\element\Chat;
use greenbot\element\User;
use greenbot\element\Group;

class Message{
    
    /* int */
    protected $message_id;//tip
    /* User or Group Class */
    protected $from;
    /* Chat Class */
    protected $chat;
    /* int */
    protected $date;
    /* String */
    protected $text;

    public function __construct($text = ''){
        $this->message_id = 0;
        $this->from = [];
        $this->chat = null;
        $this->date = 0;
        $this->text = $text;  
    }

    public function receive($msg){
        $msg = unserialize($msg);

        $this->message_id = $msg['message_id'];
        $this->from = $msg['from'];
        //$this->chat = new Chat($msg['chat']);
        switch($msg['chat']['type'])
        {
            case 'private':
                $this->chat = new User($msg['chat']);
                break;
            case 'group':
                $this->chat = new Group($msg['chat']);
                break;
        }
        $this->date = $msg['date'];
        $this->text = isset($msg['text']) ? $msg['text'] : '';
        return $this;
    }

    public function getId(){
        return $this->message_id;
    }

    public function getText(){
        return $this->text;
    }

    public function getFrom(){
        return $this->from;
    }

    public function getDate(){
        return $this->date;
    }

    public function getChat(){
        if(!($this->chat instanceof Chat))
            return false;
        return $this->chat;
    }
    public function __toString(){
        return $this->text;
    }
    
    public static function init(){
        return 'pthread hack';
    }
}