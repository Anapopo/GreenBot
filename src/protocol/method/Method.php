<?php
namespace protocol\method;

use greenbot\utils\Curl;
use greenbot\element\Bot;

use greenbot\element\Chat;
use greenbot\element\Sticker;
use greenbot\element\Photo;

use greenbot\console\MainLogger;

abstract class Method{

    private static $error = false;

    private static $url = 'https://api.telegram.org/bot';

    public static function isError(){
        return self::$error;
    }

    public static function getUpdates()
    {
        $json = (new Curl())->
        setProxy()->
        setUrl(self::$url.Bot::TOKEN.'/getupdates')->
        exec();
        $json = json_decode($json, true);
        return $json;
    }

    public static function editMessageText($chatid,$messageid,$text,$keyboard = false)
    {    
        $post = [
            'chat_id'=>$chatid,
            'message_id'=>$messageid,
            'text'=>$text
        ];

        if($keyboard){
            $extra = [
                'reply_markup'=>$keyboard
            ];
            $post = array_merge($post,$extra);
        }

        $json = (new Curl())->
        setProxy()->
        returnHeader(false)->
        setUrl(self::$url.Bot::TOKEN.'/editmessagetext')->
        setPost($post)->
        exec();
        MainLogger::info('[ID:'.$messageid.']->'.$text);
        $json = json_decode($json, true);
        return $json;
    }

    public static function editMessageKeyboard($chatid, $messageid, $keyboard = false)
    {
        $post = [
            'chat_id'=>$chatid,
            'message_id'=>$messageid,
            'reply_markup'=>$keyboard
        ];

        $json = (new Curl())->
        setProxy()->
        returnHeader(false)->
        setUrl(self::$url.Bot::TOKEN.'/editessagereplymarkup')->
        setPost($post)->
        exec();
        $json = json_decode($json, true);
        return $json;
    }

    public static function sendSticker(Chat $target, Sticker $sticker)
    {
        $post = [
            'chat_id'=>$target->getId(),
            'sticker'=>$sticker->getFileId()
        ];
        $json = (new Curl())->
        setProxy()->
        returnHeader(false)->
        setUrl(self::$url.Bot::TOKEN.'/sendsticker')->
        setPost($post)->
        exec();
        $json = json_decode($json, true);
        //print_r($json);
        return $json;   
    }

    public static function sendPhoto(Chat $target, Photo $photo, $caption = null, $keyboard = null)
    {
        $post = [
            'chat_id'=>$target->getId(),
            'photo'=>$photo->getFile(),
            'caption' =>$caption,
            'reply_markup'=>$keyboard
        ];
        $json = (new Curl())->
        setProxy()->
        returnHeader(false)->
        setUrl(self::$url.Bot::TOKEN.'/sendphoto')->
        setPost($post)->
        exec();
        $json = json_decode($json, true);
        print_r($json);
        MainLogger::info('[Me->'.$target->getName().'] (Image)');
        return $json;   
    }
}