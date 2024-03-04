<?php

namespace MediaExpert\Backend\models;

class Item
{
    public $id;
    public $name;
    public $created_at;
    public $status;

    public function getId()
    {
        return $this->id;
    }

    public function setId($_id)
    {
        $this->id = $_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($_name)
    {
        $this->name = $_name;
    }

    public function getCreateAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($_created_at)
    {
        $this->created_at = $_created_at;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($_status)
    {
        $this->status = strtoupper($_status);
    }
}