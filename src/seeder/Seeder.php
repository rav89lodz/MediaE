<?php

namespace MediaExpert\Backend\seeder;

use MediaExpert\Backend\Core\Database;

class Seeder
{
    public function seed($migration)
    {
        $db = new Database();
        $datetime = date("Y-m-d H:i:s");
        $db->query("INSERT INTO `migrations` (`id`, `name`, `created_at`) VALUES (NULL, '$migration', '$datetime')");
        $db->query("INSERT INTO `status` (`id`, `name`) VALUES (NULL, 'NEW')");
        $db->query("INSERT INTO `status` (`id`, `name`) VALUES (NULL, 'INACCESSIBLE')");
        $db->query("INSERT INTO `status` (`id`, `name`) VALUES (NULL, 'WAITING_FOR_DELIVERY')");
        $db->query("INSERT INTO `status` (`id`, `name`) VALUES (NULL, 'DAMAGED')");
    }
}