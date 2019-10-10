<?php

namespace app\exception;

use exception\BaseException;
use exception\HttpCode;

class CosException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => 'Cloud Object Storage relative exception!',
        'errcode' => 220000,
    ];

    protected $errcode = [
        220001 => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'Database unknown error! action: get data'],
    ];
}