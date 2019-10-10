<?php

namespace app\exception;

use exception\{BaseException, HttpCode};

class UserSubscriptionException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_BAD_REQUEST,
        'message' => '微信步数相关处理异常！',
        'errcode' => 150000,
    ];

    protected $errcode = [
        150001 => [HttpCode::SC_NOT_FOUND, '已无更多关注对象！'],
        150002 => [HttpCode::SC_INTERNAL_SERVER_ERROR, '取消关注用户失败！'],
    ];
}