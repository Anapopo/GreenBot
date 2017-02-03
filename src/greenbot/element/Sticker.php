<?php
namespace greenbot\element;

class Sticker{

    public $fileid;

    public function __construct($fileid){
        //if(is_string($sticker)){
            $this->fileid = $fileid;
        //}
    }

    public function getFileId(){
        return $this->fileid;
    }

    public function __toString(){}
}