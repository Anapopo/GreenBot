<?php
namespace greenbot\plugin;
use greenbot\Server;
use greenbot\message\Message;
use greenbot\query\CallbackQuery;
class PluginBase{

    protected $manager;

    public function __construct(PluginManager $manager){
        $this->manager = $manager;
    }

    public function getServer(){
        return $this->manager->getServer();
    }

    public function getManager(){
        return $this->manager;
    }

    public function getDataDir(){
        return $this->getRealDataDir() . DIRECTORY_SEPARATOR;
    }

    public function getRealDataDir(){
        $name = explode('\\', static::class);
        $dir = $this->manager->getServer()->getPluginDir() . DIRECTORY_SEPARATOR . end($name);
        if(!file_exists($dir)){
            mkdir($dir);
        }
        return $dir;
    }

    public function registerTimeCallback($time){
        $this->manager->registerTimeCallback($time, $this);
    }

    public function unregisterTimeCallback($time){
        $this->manager->unregisterTimeCallback($time, $this);
    }

    public function registerCallback(){
        $this->manager->registerCallback($this);
    }

    public function unregisterCallback(){
        $this->manager->unregisterCallback($this);
    }

    public function onLoad(){

    }

    public function onShutdown(){
        
    }

    public function onMessageReceive(Message $message){

    }

    public function onCallbackQueryReceive(CallbackQuery $query){

    }

    public function onCallback($type){

    }

}