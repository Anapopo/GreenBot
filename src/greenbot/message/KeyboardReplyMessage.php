<?php
namespace greenbot\message;

class KeyboardReplyMessage extends ReplyMessage {

    protected $keyboard;

    public function __construct(Message $msg, $text, $keyboard)
    {   
        $this->keyboard = $keyboard;
        $this->reply_to_message_id = $msg->getId();
        $this->text = $text;
    }

    public function getKeyboard(){
        return $this->keyboard;
    }


}