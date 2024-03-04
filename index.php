<?php

use MediaExpert\Backend\Core\Session;
use MediaExpert\Backend\Exceptions\AbortException;
use MediaExpert\Backend\Exceptions\ColumnNameException;
use MediaExpert\Backend\Exceptions\NewObjectException;
use MediaExpert\Backend\Exceptions\NotFoundException;
use MediaExpert\Backend\Exceptions\ParamException;
use MediaExpert\Backend\Exceptions\StatusNameException;
use MediaExpert\Backend\Exceptions\UpdateObjectException;
use MediaExpert\Backend\Exceptions\ValidationException;
use MediaExpert\Backend\Core\Response;
use MediaExpert\Backend\Core\Router;
use MediaExpert\Backend\Errors\ErrorCodes;

const BASE_PATH = __DIR__. "/";

session_start();

require BASE_PATH . 'vendor/autoload.php';
require BASE_PATH . 'src/Core/functions.php';

setDatabase();

$router = new Router();
$resposnse = new Response;

require BASE_PATH . 'routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

try
{
    $router->route($uri, $method);
}
catch(NotFoundException $e)
{
    return $resposnse->getResponse(["error" => ErrorCodes::OBJECT_NOT_FOUND], 404);
}
catch(ValidationException $e)
{
    return $resposnse->getResponse(["error" => ErrorCodes::VALIDATION_ERROR], 422);
}
catch(NewObjectException $e)
{
    $resposnse->getResponse(["error" => ErrorCodes::OBJECT_CREATING_ERROR], 400);
}
catch(ParamException $e)
{
    $resposnse->getResponse(["error" => ErrorCodes::WRONG_PARAM_TYPE], 422);
}
catch(UpdateObjectException $e)
{
    $resposnse->getResponse(["error" => ErrorCodes::OBJECT_UPDATE_ERROR], 400);
}
catch(StatusNameException $e)
{
    return $resposnse->getResponse(["error" => ErrorCodes::WRONG_STATUS_NAME], 422);
}
catch(ColumnNameException $e)
{
    return $resposnse->getResponse(["error" => ErrorCodes::WRONG_COLUMN_NAME], 409);
}
catch(AbortException $e)
{
    return $resposnse->getResponse(["error" => ErrorCodes::ABORT], 404);
}
catch(Exception $e)
{
    return $resposnse->getResponse(["error" => $e], 503);
}
Session::unflash();