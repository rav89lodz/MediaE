<?php

namespace MediaExpert\Backend\mappers;

use MediaExpert\Backend\Core\Validator;
use MediaExpert\Backend\models\Item;

class ItemMapper
{
    public function mapRequest()
    {
        $item = new Item;

        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $id = isset($obj->id) ? Validator::getValue($obj->id) : null;
        $id = Validator::isInt($id) ? $id : 0;
        $result = Validator::greaterThan($id, 0);
        if($result === false)
        {
            $id = null;
        }

        $name = isset($obj->name) ? Validator::getValue($obj->name) : null;
        $result = Validator::string($name);
        if($result === false)
        {
            $name = null;
        }

        $date = isset($obj->created_at) ? Validator::getValue($obj->created_at) : null;
        $result = Validator::isValidDate($date);
        if($result === false)
        {
            $date = null;
        }

        $status = isset($obj->status) ? Validator::getValue($obj->status) : null;
        $result = Validator::string($status);
        if($result === false)
        {
            $status = null;
        }

        $item->setId($id);
        $item->setName($name);
        $item->setCreatedAt($date);
        $item->setStatus($status);

        return $item;
    }

    public function mapResponse($data)
    {
        if($data === false)
        {
            return [];
        }
        $obj = new Item;
        $obj->setId($data['id']);
        $obj->setName($data['name']);
        $obj->setCreatedAt($data['created_at']);
        $obj->setStatus($data['status_name']);
        return get_object_vars($obj);
    }

    public function mapResponseArray($data)
    {
        if($data === false)
        {
            return [];
        }
        $elements = [];
        foreach($data as $key => $value)
        {
            $obj = new Item;
            $obj->setId($value['id']);
            $obj->setName($value['name']);
            $obj->setCreatedAt($value['created_at']);
            $obj->setStatus($value['status_name']);
            $elements[] = get_object_vars($obj);
        }
        return ['data' => $elements];
    }
}