<?php
namespace protocol\io;

use protocol\Queue;
use greenbot\element\Bot;

use greenbot\message\UpdateQueue;

use greenbot\utils\Curl;
use greenbot\console\Mainlogger;
use greenbot\console\TextFormat;

class UpdateReceiver extends Receiver{

    private $messagebox;
    private $querybox;
    private $polltimes;

    public function __construct()
    {   
        parent::__construct();
        $this->messagebox = Queue::getInstance()->getMessagebox();
        $this->querybox = Queue::getInstance()->getQuerybox();
        $this->polltimes = 0;
    }

    public function run()
    {
        $curl = new Curl();
        while(!$this->shutdown)
        {
            $offset = !isset($this->last_update_id) ? 0 : ($this->last_update_id + 1);

            //MainLogger::warning('开始执行第 ['.($this->polltimes + 1).'] 次长轮询[Long Polling]~');
            //$start = time();

            $json = $curl->
            setProxy()->
            returnHeader(false)->
            setUrl('https://api.telegram.org/bot'.Bot::TOKEN.'/getupdates')->
            setTimeout(64)->
            setPost(['offset'=>$offset,'limit'=>32,'timeout'=>64])->
            exec();

            //$last = time() - $start;
            $this->polltimes++;
            //MainLogger::warning('本次长轮询[Long Polling]耗时'.$last.'s');

            if ($json == false) {//可能是代理服务器出问题
                MainLogger::alert('请检查Shadowsocks的连接状态~');
                continue;
            }

            //MainLogger::info($json);

            $json = json_decode($json, true);
            
            if (empty($json['result'])) {//无数据获取
                //MainLogger::warning('没有新数据的说，那我开启新的一轮啦...');
                usleep(500000);
                continue;
            }
            //print_r($json['result']);
            //MainLogger::info(TextFormat::AQUA.'收到新数据啦，那我处理一下数据...');
            foreach($json['result'] as $update)
            {   
                if($update['update_id'] > $this->last_update_id)
                {   
                    //print_r($update);
                    if(isset($update['message']))
                    {   
                        MainLogger::info('收到Message');
                        $msg = $update['message'];
                        $this->messagebox['in'][] = serialize($msg);
                        //var_dump($this->messagebox);
                    }

                    if(isset($update['callback_query']))
                    {   
                        MainLogger::info('收到CallbackQuery');
                        $query = $update['callback_query'];
                        //print_r($query);
                        $this->querybox['in'][] = serialize($query);
                    }

                    if(isset($update['inline_query']))
                    {
                        MainLogger::info('收到InlineQuery');
                        $query = $update['inline_query'];
                        print_r($query);//TODO
                    }
                }
            }

            $last_update = end($json['result']);
            $this->last_update_id = $last_update['update_id'];
            //MainLogger::info('当前update_id='.$this->last_update_id);
            
        }
    }

}
