<?php
namespace greenbot\element;
use greenbot\message\Message;
use greenbot\message\MessageQueue;
class Group extends Chat {

    private $title;

    public function __construct(array $chat)
    {
        $this->id = $chat['id'];
        $this->title = $chat['title'];
        $this->type = $chat['type'];
    }

    public function getName(){
        return $this->title;
    }




}