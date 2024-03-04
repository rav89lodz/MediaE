<?php

namespace MediaExpert\Backend\interfaces;

interface BaseInterface
{
    function getAll();
    function getById($id);
    function delete($id);
}