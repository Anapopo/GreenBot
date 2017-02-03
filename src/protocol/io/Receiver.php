<?php
namespace protocol\io;

use greenbot\element\Bot;
use greenbot\utils\Curl;
use greenbot\message\Message;
use greenbot\console\MainLogger;
use greenbot\console\TextFormat;

class Receiver extends \Thread{

    protected $shutdown;

    protected $last_update_id;

    public function __construct()
    {   
        Bot::init();
        Curl::init();
        Message::init();
        TextFormat::init();
        MainLogger::init();
        $this->shutdown = false;
        $this->last_update_id = 0;
    }

    public function run() {}

    public function shutdown() {
        $this->shutdown = true;
    }
}
