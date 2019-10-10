<?php

namespace app\exception;

use exception\BaseException;
use exception\HttpCode;

class UserPrizeException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_BAD_REQUEST,
        'message' => '用户奖品相关处理异常！',
        'errcode' => 210000,
    ];

    protected $errcode = [
        210001 => [HttpCode::SC_NOT_FOUND, '未找到对应奖品！'],
    ];
}