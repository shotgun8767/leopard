<?php

namespace app\exception\activity;

use exception\BaseException;
use exception\HttpCode;

class ActivityException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => '活动异常！',
        'errcode' => 170000,
        'data' => []
    ];

    protected $errcode = [
        170001 => [HttpCode::SC_NOT_FOUND, '活动不存在！'],
    ];
}