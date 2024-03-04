<?php

namespace MediaExpert\Backend\Core;

class Response
{
    public function getResponse($data, $code)
    {
        http_response_code($code);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        die();
    }
}