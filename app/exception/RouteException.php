<?php

namespace app\exception;

use exception\{BaseException, HttpCode};

class RouteException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_UNAUTHORIZED,
        'message' => 'Route relative exception!',
        'errcode' => 40000,
    ];

    protected $errcode = [
        40001 => [HttpCode::SC_METHOD_NOT_ALLOWED, 'No such rule, or the method is not allowed for the rule!'],
    ];
}