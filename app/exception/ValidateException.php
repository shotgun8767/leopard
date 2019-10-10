<?php

namespace app\exception;

use exception\{BaseException, HttpCode};

class ValidateException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_BAD_REQUEST,
        'message' => 'validate relative exception！',
        'errcode' => 10000,
    ];

    protected $errcode = [
        10001 => [HttpCode::SC_BAD_REQUEST, 'Missing required parameter!'],
        10002 => [HttpCode::SC_NOT_IMPLEMENTED, 'Validate error!'],
        10003 => [HttpCode::SC_NOT_IMPLEMENTED, 'Missing type of parameter!'],
        10004 => [HttpCode::SC_NOT_IMPLEMENTED, 'Length of parameter should be positive integer!'],
        10005 => [HttpCode::SC_UNPROCESSABLE_ENTITY, 'Length of parameter exceeds maximum!'],
        10006 => [HttpCode::SC_UNPROCESSABLE_ENTITY, 'parameter validation fails!'],
        10007 => [HttpCode::SC_UNPROCESSABLE_ENTITY, 'parameter exists illegal chars!'],
        10008 => [HttpCode::SC_UNPROCESSABLE_ENTITY, 'parameter validation fails!'],
    ];
}