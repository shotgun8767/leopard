<?php

namespace app\exception;

use exception\{BaseException, HttpCode};

class UserRunException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_BAD_REQUEST,
        'message' => '微信步数相关处理异常！',
        'errcode' => 200000,
    ];

    protected $errcode = [
        200001 => [HttpCode::SC_BAD_REQUEST, '步数格式错误！'],
        200002 => [HttpCode::SC_FORBIDDEN, '今日已用步数换取积分！'],
        200003 => [HttpCode::SC_BAD_REQUEST, '缺失必要的参数！'],
        200004 => [HttpCode::SC_BAD_REQUEST, '参数错误！'],
        200005 => [HttpCode::SC_INTERNAL_SERVER_ERROR, '敏感信息解码失败！']
    ];
}