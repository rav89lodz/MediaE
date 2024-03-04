<?php

namespace MediaExpert\Backend\Core;

use MediaExpert\Backend\migrations\DbWorker;
use MediaExpert\Backend\seeder\Seeder;
use PDO;
use PDOException;

class Database
{
    public $connection;
    public $statement;

    public function __construct()
    {
        $config = require BASE_PATH . ('config.php');
        $dsn = 'mysql:' . http_build_query($config["database"], '', ';');

        $this->connection = new PDO($dsn, $config["database"]["username"], $config["database"]["password"], [
           PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);
        try
        {
            $this->statement->execute($params);
            return $this;
        }
        catch(PDOException $e)
        {
            return;
        }
    }

    public function get()
    {
        return $this->statement->fetchAll();
    }

    public function find()
    {
        return $this->statement->fetch();
    }

    public function createDB($migration)
    {
        $dbWorker = new DbWorker();
        $seeder = new Seeder;

        $dbWorker->run($migration);
        $seeder->seed($migration);
    }
}
