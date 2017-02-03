<?php
namespace greenbot\element;

class KeyboardButton{

    public static function buildNormal($text, $request_contact = false, $request_location = false)
    {
        $replyMarkup = array(
            'text' => $text,
            'request_contact' => $request_contact,
            'request_location' => $request_location
        );
        if ($url != "") {
            $replyMarkup['url'] = $url;
        } else if ($callback_data != "") {
            $replyMarkup['callback_data'] = $callback_data;
        } else if ($switch_inline_query != "") {
            $replyMarkup['switch_inline_query'] = $switch_inline_query;
        }
        return $replyMarkup;
    }

    public static function buildInline($text, $url = "", $callback_data = "", $switch_inline_query = "")
    {
        $replyMarkup = array(
            'text' => $text
        );
        if ($url != "") {
            $replyMarkup['url'] = $url;
        } else if ($callback_data != "") {
            $replyMarkup['callback_data'] = $callback_data;
        } else if ($switch_inline_query != "") {
            $replyMarkup['switch_inline_query'] = $switch_inline_query;
        }
        return $replyMarkup;
    }
}