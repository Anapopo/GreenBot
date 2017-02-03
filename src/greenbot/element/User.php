<?php
namespace greenbot\element;
use greenbot\message\MessageQueue;
use greenbot\message\Message;
use greenbot\message\ReplyMessage;
class User extends Chat{

    public function __construct(array $chat)
    {
        parent::__construct($chat);
    }

    public function getName(){
        return ($this->username != null) ? $this->username : 
        ($this->first_name != null)?$this->first_name:false;
    }
    
}