<?php

namespace MediaExpert\Backend\Http\services;

use MediaExpert\Backend\Core\Database;
use MediaExpert\Backend\Exceptions\NewObjectException;
use MediaExpert\Backend\Exceptions\NotFoundException;
use MediaExpert\Backend\Exceptions\ParamException;
use MediaExpert\Backend\Exceptions\StatusNameException;
use MediaExpert\Backend\Exceptions\UpdateObjectException;
use MediaExpert\Backend\Core\Response;
use MediaExpert\Backend\Core\Validator;
use MediaExpert\Backend\interfaces\BaseInterface;
use MediaExpert\Backend\interfaces\StatusInterface;
use MediaExpert\Backend\mappers\StatusMapper;
use MediaExpert\Backend\models\Status;

class StatusService implements BaseInterface, StatusInterface
{
    private $db;
    private $resposnse;
    private $statusMapper;

    public function __construct()
    {
        $this->db = new Database();
        $this->resposnse = new Response;
        $this->statusMapper = new StatusMapper;
    }

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM `status`")->get();
        return $this->resposnse->getResponse($this->statusMapper->mapResponseArray($result), 200);
    }

    public function getById($id)
    {
        $id = $this->validateId($id);
        $result = $this->db->query("SELECT * FROM `status` WHERE id = $id")->find();
        return $this->resposnse->getResponse($this->statusMapper->mapResponse($result), 200);
    }

    public function getByName($name)
    {
        $nameTest = Validator::string($name);
        if($nameTest === false)
        {
            throw new StatusNameException();
        }
        $result = $this->db->query("SELECT * FROM `status` WHERE `name` = '$name'")->find();
        return $result;
    }

    public function create(Status $status)
    {
        if($this->checkObjectIsValid($status) == false)
        {
            throw new NewObjectException();
        }
        $name = $status->getName();
        $this->db->query("INSERT INTO `status` (`id`, `name`) VALUES (NULL, '$name')");
        return $this->resposnse->getResponse(["Success" => "Created"], 201);
    }

    public function edit(Status $status)
    {
        $id = $this->validateId($status->getId());
        if($this->checkObjectIsValid($status) == false || $this->checkObjectExists($id) == false)
        {
            throw new UpdateObjectException();
        }
        $name = $status->getName();
        $this->db->query("UPDATE `status` SET `name` = '$name' WHERE `status`.`id` = $id");
        return $this->resposnse->getResponse(["Success" => "Updated"], 200);
    }

    public function delete($id)
    {
        $id = $this->validateId($id);
        if($this->checkObjectExists($id) == false)
        {
            throw new NotFoundException();
        }
        $this->db->query("DELETE FROM `status` WHERE id = $id");
        return $this->resposnse->getResponse(["Success" => "OK"], 200);
    }

    private function checkObjectIsValid(Status $status)
    {
        if($status->getName() == null)
        {
            return false;
        }
        return true;
    }

    private function checkObjectExists($id)
    {
        $result = $this->db->query("SELECT * FROM `status` WHERE id = $id")->find();
        if($result === false)
        {
            return false;
        }
        return true;
    }

    private function validateId($id)
    {
        if(Validator::validateId($id) === false)
        {
            throw new ParamException();
        }
        return $id;
    }
}