<?php

use MediaExpert\Backend\Core\Router;

$router = new Router();

$router->get('/', 'items\ItemController', 'index');
$router->get('/items/show', 'items\ItemController', 'show');
$router->get('/items/search', 'items\ItemController', 'search');
$router->get('/items/history', 'items\ItemController', 'history');
$router->delete('/items/delete', 'items\ItemController', 'delete');
$router->post('/items/add', 'items\ItemController', 'create');
$router->put('/items/edit', 'items\ItemController', 'edit');

$router->get('/status', 'status\StatusController', 'index');
$router->get('/status/show', 'status\StatusController', 'show');
$router->delete('/status/delete', 'status\StatusController', 'delete');
$router->post('/status/add', 'status\StatusController', 'create');
$router->put('/status/edit', 'status\StatusController', 'edit');
