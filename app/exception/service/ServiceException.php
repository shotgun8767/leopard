<?php

namespace app\exception\service;

use exception\{BaseException, HttpCode};

class ServiceException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => 'Service relative exception!',
        'errcode' => 30000,
    ];
    
    protected $errcode = [

    ];
}