<?php

namespace app\exception;

use exception\BaseException;
use exception\HttpCode;

class WxException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_BAD_REQUEST,
        'message' => 'Wechat relative exception！',
        'errcode' => 110000,
    ];

    protected $errcode = [
        110001 => [HttpCode::SC_BAD_GATEWAY, 'Fail to get openid from wechat backend!'],
        110002 => [HttpCode::SC_BAD_GATEWAY, 'Fail to get openid from wechat backend! Error result returned!'],
        110003 => [HttpCode::SC_BAD_GATEWAY, 'Token is missing or token given is invalid.'],
    ];
}