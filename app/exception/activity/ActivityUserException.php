<?php

namespace app\exception\activity;

use exception\BaseException;
use exception\HttpCode;

class ActivityUserException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => '活动异常！',
        'errcode' => 172000,
        'data' => []
    ];

    protected $errcode = [
        172001 => [HttpCode::SC_FORBIDDEN, '用户已报名活动，无需重复报名！'],
        172002 => [HttpCode::SC_FORBIDDEN, '用户尚未报名指定活动！'],
        172003 => [HttpCode::SC_NOT_FOUND, '指定活动不存在或已被删除！'],
    ];
}