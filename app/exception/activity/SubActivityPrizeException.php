<?php

namespace app\exception\activity;

use exception\BaseException;
use exception\HttpCode;

class SubActivityPrizeException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => '活动异常！',
        'errcode' => 171000,
        'data' => []
    ];

    protected $errcode = [
        171001 => [HttpCode::SC_NOT_FOUND, '本活动没有活动奖品！'],
        171002 => [HttpCode::SC_BAD_REQUEST, '参数缺少奖品图片！'],
        171003 => [HttpCode::SC_OK, '本次操作没有修改奖品信息！'],
    ];
}