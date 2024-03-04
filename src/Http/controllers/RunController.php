<?php

namespace MediaExpert\Backend\Http\controllers;

$function = $_SESSION['function'];
$controller = "MediaExpert\Backend\Http\controllers\\" . $_SESSION['controller'];
$runController = new $controller();
$runController->$function();