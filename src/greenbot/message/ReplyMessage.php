<?php
namespace greenbot\message;

class ReplyMessage extends Message {
    
    /* int */
    protected $reply_to_message_id;

    public function __construct(Message $msg, $text)
    {
        $this->reply_to_message_id = $msg->getId();
        $this->text = $text;
    }

    public function getReplyId(){
        return $this->reply_to_message_id;
    }
}