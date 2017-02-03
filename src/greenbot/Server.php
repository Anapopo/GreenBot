<?php
namespace greenbot;

use greenbot\console\MainLogger;
use greenbot\message\MessageQueue;
use protocol\Queue;
use protocol\Protocol;

use greenbot\plugin\PluginManager;

class Server {

    const PLUGIN_DIR = 'plugins';

    private $shutdown;
    private $logger;

    private $protocol;
    private $queue;
    private $plugin;

    public function __construct(MainLogger $logger)
    {   
        MainLogger::info('GreenBot 初号机正在启动中...');
        $this->shutdown = false;
        $this->logger = $logger;
        
        $this->queue = new Queue();
        
        $this->protocol = new Protocol();
        $this->protocol->run();
        
        $this->plugin = new PluginManager($this);
        $this->plugin->load();
        MainLogger::info("GreenBot 初号机启动完毕!");
        $this->main();
    }

    public function main()
    {
        while(!$this->shutdown)
        {   
            $this->plugin->doTick();
            usleep(50000);
        }
    }

    public function getPluginManager(){
        return $this->plugin;
    }

    public function getPluginDir(){
        return \greenbot\BASE_DIR . DIRECTORY_SEPARATOR . self::PLUGIN_DIR;
    }



}