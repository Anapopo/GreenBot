<?php
namespace greenbot\query;

class InlineQuery{

    private $id;

    private $from;

    private $query;

    private $offset;

    public function __construct(){
        $this->id = 0;
        $this->from = [];
        $this->query = null;
        $this->offset = null;
    }

    public function receive($query)
    {
        $query = unserialize($query);

        $this->id = $query['id'];
        $this->from = $query['from'];
        $this->query = $query['query'];
        $this->offset = $query['offset'];
        return $this;
    }

}