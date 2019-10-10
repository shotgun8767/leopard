<?php

namespace app\exception;

use exception\{BaseException, HttpCode};

class DatabaseException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => 'Database relative exception!',
        'errcode' => 20000,
    ];

    protected $errcode = [
        20001 => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'Database unknown error! action: get data'],
        20002 => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'Database unknown error! action: updating data.'],
        20003 => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'Database unknown error! action: inserting data.'],
        20004 => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'Database unknown error! action: deleting data.'],
        20005 => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'Fail to connect to redis!'],
    ];
}