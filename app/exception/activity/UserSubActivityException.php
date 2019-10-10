<?php

namespace app\exception\activity;

use exception\BaseException;
use exception\HttpCode;

class UserSubActivityException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => '活动异常！',
        'errcode' => 172000,
        'data' => []
    ];

    protected $errcode = [
        172001 => [HttpCode::SC_NOT_FOUND, '子活动不存在！'],
        172002 => [HttpCode::SC_OK, '用户已报名，无需重复报名！'],
        172003 => [HttpCode::SC_FORBIDDEN, '子活动报名人数已满！'],
        172004 => [HttpCode::SC_FORBIDDEN, '未到子活动开始报名时间！'],
        172005 => [HttpCode::SC_FORBIDDEN, '已过子活动结束报名时间！'],
        172006 => [HttpCode::SC_FORBIDDEN, '子活动已开始或结束，不允许报名！'],
        172007 => [HttpCode::SC_FORBIDDEN, '子活动报名结束，不允许取消报名！'],
    ];
}