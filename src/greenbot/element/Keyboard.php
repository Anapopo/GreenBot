<?php
namespace greenbot\element;

class Keyboard{

    public function __construct(){}

    public static function buildNormal(array $options, $onetime = false, $resize = false, $selective = true){
        $replyMarkup = array(
            'keyboard' => $options,
            'one_time_keyboard' => $onetime,
            'resize_keyboard' => $resize,
            'selective' => $selective
        );
        $encodedMarkup = json_encode($replyMarkup);
        return $encodedMarkup;
    }

    public static function buildInline(array $options)
    {
        $replyMarkup = array(
            'inline_keyboard' => $options
        );
        $encodedMarkup = json_encode($replyMarkup);
        return $encodedMarkup;
    }
}