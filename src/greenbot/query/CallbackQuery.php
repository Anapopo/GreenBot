<?php
namespace greenbot\query;

use protocol\Queue;
use protocol\method\Method;

use greenbot\element\Chat;
use greenbot\element\User;
use greenbot\element\Group;

class CallbackQuery{

    private $id;

    private $data;

    private $message;

    private $chat;

    public function __construct(){
        $this->id = 0;
        $this->data = null;
        $this->message = [];
    }

    public function receive($query)
    {
        $query = unserialize($query);
        $this->id = $query['id'];
        $this->data = $query['data'];
        $this->message = $query['message'];

        switch($query['message']['chat']['type'])
        {
            case 'private':
                $this->chat = new User($query['message']['chat']);
                break;
            case 'group':
                $this->chat = new Group($query['message']['chat']);
                break;
        }
        return $this;
    }

    public function answer($text, $alert = false){
        Queue::getInstance()->answerQuery($this,$text,$alert);
    }

    public function editText($text, $keyboard = false){
        Method::editMessageText($this->getChatId(),$this->getMessageId(),$text,$keyboard);
    }
    public function editKeyboard($keyboard){
        Method::editMessageKeyboard($this->getChatId(),$this->getMessageId(),$keyboard);
    }

    public function getMessageId(){
        return $this->message['message_id'];
    }

    public function getId(){
        return $this->id;
    }

    public function getChat(){
        if(!($this->chat instanceof Chat))
            return false;
        return $this->chat;
    }

    public function getChatId(){
        return $this->message['chat']['id'];
    }

    public function getData(){
        return $this->data;
    }
    

}