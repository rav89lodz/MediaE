<?php

namespace MediaExpert\Backend\Http\controllers;

use MediaExpert\Backend\Core\Validator;
use MediaExpert\Backend\interfaces\BaseInterface;

abstract class BaseController
{
    private $_service;

    public function __construct(BaseInterface $service)
    {
        $this->_service = $service;
    }

    public function index()
    {
        return $this->_service->getAll();
    }

    public function show()
    {
        return $this->_service->getById(Validator::getValue($_GET['id']));
    }

    public function delete()
    {
        return $this->_service->delete(Validator::getValue($_GET['id']));
    }
}