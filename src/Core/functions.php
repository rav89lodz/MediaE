<?php

use MediaExpert\Backend\Core\Database;
use MediaExpert\Backend\Core\Session;

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function redirect($path)
{
    header("location: {$path}");
    exit();
}

function old($key, $default = '')
{
    return Session::get('old')[$key] ?? $default;
}

function setDatabase()
{
    $db = new Database();
    $migrations = scandir(BASE_PATH . "src/migrations", SCANDIR_SORT_DESCENDING);
    $migration = str_replace(".php", "", $migrations[0]);
    try
    {
        $result = $db->query("SELECT * FROM migrations WHERE name = '$migration'");
        if(count($result->get()) == 0)
        {
            $db->createDB($migration);
        }
        return;
    }
    catch(PDOException $e)
    {
        $db->createDB($migration);
        return;
    }
}