<?php

namespace MediaExpert\Backend\models;

class Search
{
    public $available_columns = ["status", "name", "created_at"];
    public $column;
    public $data;

    public function getColumn()
    {
        return $this->column;
    }

    public function setColumn($_column)
    {
        $this->column = $_column;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($_data)
    {
        $this->data = $_data;
    }
}