<?php
namespace greenbot\element;

class Photo{

    private $file;

    private $fileid;

    //private $bindata;
    //5 MB max size for photos and 20 MB max for other types of content. HTTP URL
    //10 MB max size for photos, 50 MB for other files. multipart/form-data
    public function __construct($some = 'BQADBQADKgADqRNhB6IiZ1NKS0jsAg'){
        if(is_file($some)){
            $this->file = new \CURLFile(realpath($some));
        }else{
            $this->file = $some;
        }


    }

    public function getFile(){
        return $this->file;
    }

    public function __toString(){}
}
//new CURLFile(realpath($urlphoto)));