<?php

namespace MediaExpert\Backend\Http\controllers\items;

use MediaExpert\Backend\Http\controllers\BaseController;
use MediaExpert\Backend\Http\services\ItemService;
use MediaExpert\Backend\mappers\ItemMapper;
use MediaExpert\Backend\mappers\SearchMapper;

class ItemController extends BaseController
{
    private $_service;
    private $_mapper;
    private $_sMapper;

    public function __construct()
    {
        $this->_service = new ItemService;
        $this->_mapper = new ItemMapper;
        $this->_sMapper = new SearchMapper;
        parent::__construct($this->_service);
    }

    public function __invoke()
    {
        $function = $_SESSION['function'];
        return $function();
    }

    public function create()
    {
        return $this->_service->create($this->_mapper->mapRequest());
    }

    public function edit()
    {
        return $this->_service->edit($this->_mapper->mapRequest());
    }

    public function search()
    {
        return $this->_service->searchBy($this->_sMapper->mapRequest());
    }

    public function history($id)
    {
        return $this->_service->getItemHisory($id);
    }
}