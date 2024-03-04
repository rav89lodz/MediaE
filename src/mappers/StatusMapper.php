<?php

namespace MediaExpert\Backend\mappers;

use MediaExpert\Backend\Core\Validator;
use MediaExpert\Backend\models\Status;

class StatusMapper
{
    public function mapRequest()
    {
        $status = new Status;

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
        
        $status->setId($id);
        $status->setName($name);

        return $status;
    }

    public function mapResponse($data)
    {
        if($data === false || $data === null)
        {
            return [];
        }
        $obj = new Status;
        $obj->setName($data['name']);
        $obj->setId($data['id']);
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
            $obj = new Status;
            $obj->setName($value['name']);
            $obj->setId($value['id']);
            $elements[] = get_object_vars($obj);
        }
        return ['data' => $elements];
    }
}