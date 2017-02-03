<?php
namespace greenbot\utils;

class Curl{
    private $curl;
    private $url;
    private $content;

    public function __construct(){
        $this->reload();
    }

    public static function init(){
        return 'pthread hack';
    }

    public function reload(){
        if(is_resource($this->curl)){
            curl_close($this->curl);
        }
        $this->curl = curl_init();
        if(substr(php_uname(), 0, 7) == "Windows"){
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        }//Stupid Windows
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $this->returnHeader(true);
        //$this->setTimeout(10);
        return $this;
    }

    public function setUA($ua){
        curl_setopt($this->curl, CURLOPT_USERAGENT, $ua);
        return $this;
    }

    public function setUrl($url){
        $this->url = $url;
        curl_setopt($this->curl, CURLOPT_URL, $url);
        return $this;
    }

    public function returnHeader($bool){
        curl_setopt($this->curl, CURLOPT_HEADER, ($bool == true) ? 1 : 0);
        return $this;
    }

    public function returnBody($bool){
        curl_setopt($this->curl, CURLOPT_NOBODY, ($bool == false) ? 1 : 0);
        return $this;
    }

    public function followLocation($bool){
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, ($bool == true) ? 1 : 0);
        return $this;
    }
    
    public function setHeader($arr){
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $arr);
        return $this;
    }

    public function setProxy($proxy = '127.0.0.1:1080'){
        curl_setopt($this->curl, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($this->curl, CURLOPT_PROXY, $proxy);
        return $this;
    }
    
    public function setCookie($cookies){
        $payload = '';
        foreach($cookies as $key=>$cookie){
            $payload .= "$key=$cookie; ";
        }
        curl_setopt($this->curl, CURLOPT_COOKIE, $payload);
        return $this;
    }

    public function useCookie($filename){
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $filename);
        return $this;
    }
    public function saveCookie($filename){
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $filename); 
        return $this;
    }

    public function setReferer($referer){
        curl_setopt($this->curl, CURLOPT_REFERER, $referer);
        return $this;
    }

    public function setGet($get){
        $payload = '?';
        foreach($get as $key=>$content){
            $payload .= urlencode($key).'='.urlencode($content).'&';
        }
        curl_setopt($this->curl, CURLOPT_URL, $this->url.$payload);
        return $this;
    }

    public function setPost($post){
        $payload = '';
        foreach($post as $key=>$content){
            $payload .= urlencode($key).'='.urlencode($content).'&';
        }
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $payload);
        return $this;
    }

    public function setEncPost($post){
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
        return $this;
    }

    public function setTimeout($timeout){
	    curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $timeout);
        return $this;
    }

    public function keepCookie(){
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, '');
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, '');
        return $this;
    }

    public function exec(){
        $this->content = curl_exec($this->curl);
        $this->reload();
        return $this->content;
    }

    public function getCookie(){
        preg_match_all('/Set-Cookie: (.*);/iU', $this->content, $cookies);
        $payload = [];
        foreach($cookies[1] as $cookie){
            $key = explode('=', $cookie);
            if(isset($payload[$key[0]]) and $payload[$key[0]] !== ''){
                continue;
            }
            $payload[$key[0]] = $key[1];
        }
		return $payload;
    }

    public function isError(){
        return (curl_errno($this->curl)) ? true : false;
    }
}