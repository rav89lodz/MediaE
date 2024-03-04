<?php

namespace MediaExpert\Backend\migrations;

class DbWorker
{
    public function run($className)
    {
        $path = "MediaExpert\Backend\migrations\\" . $className;
        $migration = new $path;
        $migration->up();
    }
}