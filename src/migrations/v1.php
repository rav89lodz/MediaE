<?php

namespace MediaExpert\Backend\migrations;

use MediaExpert\Backend\Core\Database;

final class v1
{
    public function up()
    {
        $db = new Database();
        $db->query("CREATE TABLE migrations (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NULL, created_at DATETIME, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB");
        $db->query("CREATE TABLE items (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NULL, created_at DATE, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB");
        $db->query("CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB");
        $db->query("CREATE TABLE items_history (id INT AUTO_INCREMENT NOT NULL, item_id INT NOT NULL, status_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB");
    }

    public function down()
    {
        $db = new Database();
        $db->query("DROP TABLE items_history");
        $db->query("DROP TABLE items");
        $db->query("DROP TABLE status");
    }
}