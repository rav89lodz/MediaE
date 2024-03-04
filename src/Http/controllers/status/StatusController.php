<?php

namespace MediaExpert\Backend\Http\controllers\status;

use MediaExpert\Backend\Http\controllers\BaseController;
use MediaExpert\Backend\Http\services\StatusService;
use MediaExpert\Backend\mappers\StatusMapper;

class StatusController extends BaseController
{
    private $_service;
    private $_mapper;

    public function __construct()
    {
        $this->_service = new StatusService;
        $this->_mapper = new StatusMapper;
        parent::__construct($this->_service);
    }

    public function create()
    {
        return $this->_service->create($this->_mapper->mapRequest());
    }

    public function edit()
    {
        return $this->_service->edit($this->_mapper->mapRequest());
    }
}