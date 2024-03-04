<?php

namespace MediaExpert\Backend\Http\services;

use MediaExpert\Backend\Core\Database;
use MediaExpert\Backend\Exceptions\ColumnNameException;
use MediaExpert\Backend\Exceptions\NewObjectException;
use MediaExpert\Backend\Exceptions\NotFoundException;
use MediaExpert\Backend\Exceptions\ParamException;
use MediaExpert\Backend\Exceptions\StatusNameException;
use MediaExpert\Backend\Exceptions\UpdateObjectException;
use MediaExpert\Backend\Core\Response;
use MediaExpert\Backend\Core\Validator;
use MediaExpert\Backend\interfaces\BaseInterface;
use MediaExpert\Backend\interfaces\ItemInterface;
use MediaExpert\Backend\mappers\ItemMapper;
use MediaExpert\Backend\mappers\StatusMapper;
use MediaExpert\Backend\models\Item;
use MediaExpert\Backend\models\Search;

class ItemService implements BaseInterface, ItemInterface
{
    private $db;
    private $resposnse;
    private $itemMapper;
    private $statusMapper;
    private $statusService;

    public function __construct()
    {
        $this->db = new Database();
        $this->resposnse = new Response;
        $this->itemMapper = new ItemMapper;
        $this->statusMapper = new StatusMapper;
        $this->statusService = new StatusService;
    }

    public function getAll()
    {
        $result = $this->db->query("SELECT it.id, it.name AS 'name', it.created_at, st.name AS status_name FROM items it JOIN
                                    ( SELECT item_id, MAX(id) AS max_id FROM items_history GROUP BY item_id ) AS ih_max ON it.id = ih_max.item_id
                                    JOIN items_history ih ON ih.item_id = ih_max.item_id AND ih.id = ih_max.max_id
                                    JOIN status st ON st.id = ih.status_id ORDER BY it.id ASC")->get();
        return $this->resposnse->getResponse($this->itemMapper->mapResponseArray($result), 200);
    }

    public function getById($id)
    {
        $id = $this->validateId($id);
        $result = $this->db->query("SELECT it.id, it.name as 'name', it.created_at, st.name as status_name FROM items it 
                                    JOIN items_history ih ON it.id = ih.item_id JOIN `status` st ON st.id = ih.status_id
                                    WHERE it.id = $id ORDER BY ih.id DESC")->find();
        return $this->resposnse->getResponse($this->itemMapper->mapResponse($result), 200);
    }

    public function create(Item $item)
    {
        if($this->checkObjectIsValid($item) == false)
        {
            throw new NewObjectException();
        }
        $name = $item->getName();
        $date = $item->getCreateAt();
        $this->db->query("INSERT INTO `items` (`id`, `name`, `created_at`) VALUES (NULL, '$name', '$date')");
        $lastId = $this->getLastId();
        $statusId = $this->addStatusForNewObjects($lastId);
        $this->addHistory($lastId, $statusId);
        return $this->resposnse->getResponse(["Success" => "Created"], 201);
    }

    public function edit(Item $item)
    {
        $id = $this->validateId($item->getId());
        if($this->checkObjectIsValid($item) == false || $this->checkObjectExists($id) == false)
        {
            throw new UpdateObjectException();
        }
        $statusId = $this->getStatusIdByName($item->getStatus());
        $name = $item->getName();
        $date = $item->getCreateAt();
        $this->db->query("UPDATE `items` SET `name` = '$name', `created_at` = '$date' WHERE `items`.`id` = $id");
        $this->addHistory($id, $statusId);
        return $this->resposnse->getResponse(["Success" => "Updated"], 200);
    }

    public function delete($id)
    {
        $id = $this->validateId($id);
        if($this->checkObjectExists($id) == false)
        {
            throw new NotFoundException();
        }
        $this->db->query("DELETE FROM `items_history` WHERE `item_id` = $id");
        $this->db->query("DELETE FROM items WHERE id = $id");
        return $this->resposnse->getResponse(["Success" => "OK"], 200);
    }

    public function getItemHisory($id)
    {
        $id = $this->validateId($id);
        $result = $this->db->query("SELECT it.id, it.name as 'name', it.created_at, st.name as status_name FROM items it 
                                    JOIN items_history ih ON it.id = ih.item_id JOIN `status` st ON st.id = ih.status_id
                                    WHERE it.id = $id ORDER BY ih.id DESC")->get();
        return $this->resposnse->getResponse($this->itemMapper->mapResponseArray($result), 200);
    }

    public function searchBy(Search $search)
    {
        $column = $this->checkColumName($search->getColumn());
        $data = "%" . $search->getData() . "%";
        $result = $this->db->query("SELECT it.id, it.name AS 'name', it.created_at, st.name AS status_name FROM items it JOIN
                            ( SELECT item_id, MAX(id) AS max_id FROM items_history GROUP BY item_id ) AS ih_max ON it.id = ih_max.item_id
                            JOIN items_history ih ON ih.item_id = ih_max.item_id AND ih.id = ih_max.max_id
                            JOIN status st ON st.id = ih.status_id WHERE $column like '$data' ORDER BY it.id ASC")->get();
        return $this->resposnse->getResponse($this->itemMapper->mapResponseArray($result), 200);
    }

    private function checkObjectIsValid(Item $item)
    {
        if($item->getName() == null || $item->getCreateAt() == null)
        {
            return false;
        }
        return true;
    }

    private function getLastId()
    {
        $lastId = array_values($this->db->query("SELECT LAST_INSERT_ID()")->find());
        if(count($lastId) < 1)
        {
            throw new NewObjectException();
        }
        return $lastId[0];
    }

    private function addStatusForNewObjects($id)
    {
        $statusObj = $this->statusMapper->mapResponse($this->statusService->getByName("NEW"));
        if(count($statusObj) < 1)
        {
            $this->db->query("DELETE FROM items WHERE id = $id");
            throw new NewObjectException();
        }
        return $statusObj['id'];
    }

    private function addHistory($id, $statusId)
    {
        $this->db->query("INSERT INTO `items_history` (`id`, `item_id`, `status_id`) VALUES (NULL, '$id', '$statusId')");
    }

    private function getStatusIdByName($status)
    {
        $statusObj = $this->statusMapper->mapResponse($this->statusService->getByName($status));
        if(count($statusObj) < 1)
        {
            throw new StatusNameException();
        }
        return $statusObj['id'];
    }

    private function checkColumName($column)
    {
        if($column == null || empty($column))
        {
            throw new ColumnNameException();
        }
        return $column;
    }

    private function checkObjectExists($id)
    {
        $result = $this->db->query("SELECT * FROM items WHERE id = $id")->find();
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