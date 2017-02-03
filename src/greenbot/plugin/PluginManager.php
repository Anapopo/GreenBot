<?php
namespace greenbot\plugin;

use greenbot\console\MainLogger;

use protocol\Queue;

use greenbot\Server;

class PluginManager {

    const CallBack = 1;
    const TimeCallBack = 2;

    private $server;
    private $plugins;

    private $callbacks;
    private $timecallbacks;

    public function __construct(Server $server){
        $this->server = $server;
        $this->plugins = [];
        $this->callbacks = [];
        $this->timecallbacks = [];
    }

    public function getPlugin(){
        return $this->plugins;
    }

    public function doTick(){
        foreach($this->timecallbacks as $key => $callback){
            if(time() >= $callback[0]){
                unset($this->timecallbacks[$key]);
                $callback[1]->onCallback(PluginManager::TimeCallBack);
            }
        }
        foreach($this->callbacks as $plugin){
            $plugin->onCallback(PluginManager::CallBack);
        }
        if($msg = Queue::getInstance()->getMessage()){
            foreach($this->plugins as $plugin){
                $plugin->onMessageReceive($msg);
            }
        }

        if($query = Queue::getInstance()->getQuery()){
             foreach($this->plugins as $plugin){
                $plugin->onCallbackQueryReceive($query);
            }
        }
    }

    public function shutdown(){
        foreach($this->plugins as $plugin){
            $plugin->onShutdown();
        }
    }
    
    public function registerCallback($class){
        if(!in_array($class, $this->callbacks)){
            $this->callbacks[] = $class;
        }
    }

    public function unregisterCallback($class){
        foreach($this->callbacks as $key => $c){
            if($c === $class){
                unset($this->callbacks[$key]);
            }
        }
    }

    public function registerTimeCallback($time, $class){
        $this->timecallbacks[] = [$time, $class];
    }

    public function unregisterTimeCallback($time, $class){
        foreach($this->timecallbacks as $key => $cb){
            if($cb[0] === $time && $cb[1] === $class){
                unset($this->timecallbacks[$key]);
            }
        }
    }

    public function load(){
        $dir = $this->server->getPluginDir();
        if(!file_exists($dir)){
            mkdir($dir);
        }
        $dir_array = scandir($dir);
        foreach($dir_array as $file){
            $pre = explode('.', $file);
            if(isset($pre[1])){
                if($pre[1] == 'php'){
                    MainLogger::info("尝试加载插件: {$pre[0]}");
                    include($dir . DIRECTORY_SEPARATOR . "$file");
                    $plg_class = "plugin\\{$pre[0]}";
                    $plugin = new $plg_class($this);
                    if($plugin->onLoad() === false){
                        MainLogger::alert("尝试加载插件: {$pre[0]} 时自检错误，放弃插件加载");
                    }else{
                        $this->plugins[$pre[0]] = $plugin;
                    }
                }
            }
        }
    }

    public function getServer(){
        return $this->server;
    }
}
