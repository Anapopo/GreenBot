<?php
namespace greenbot\element;

use protocol\Queue;
use protocol\method\Method;
use greenbot\message\Message;
use greenbot\element\Photo;
use greenbot\element\Sticker;
use greenbot\message\ReplyMessage;
use greenbot\message\KeyboardReplyMessage;

class Chat {

    protected $id;
    protected $first_name;
    protected $last_name;
    protected $username;
    protected $type;


    public function __construct(array $chat)
    {
       $this->id = $chat['id'];
       $this->first_name = $chat['first_name'];
       $this->last_name = isset($chat['last_name']) ? $chat['last_name'] : null;
       $this->username = isset($chat['username']) ? $chat['username'] : null;
       $this->type = $chat['type'];
    }

    public function getId(){
        return $this->id;
    }

    public function getType(){
        return $this->type;
    }

    public function sendMessage($msg)
    {
        $msg = new Message($msg);
        Queue::getInstance()->sendMessage($this,$msg);
        return true;
    }

    public function sendSticker($some)
    {
        $sticker = new Sticker($some);
        Method::sendSticker($this,$sticker);
        return true;
    }

    public function sendPhoto($some, $caption = null, $keyboard = false)
    {
        $send = new Photo($some);
        Method::sendPhoto($this,$send,$caption,$keyboard);
        return true;
    }

    public function replyMessage(Message $msg, $replytext, $keyboard=false)
    {
        $msg = $keyboard ? (new KeyboardReplyMessage($msg,$replytext,$keyboard)) : (new ReplyMessage($msg,$replytext));
        Queue::getInstance()->sendMessage($this,$msg);
        return true;
    } 
    
}