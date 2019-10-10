<?php

namespace app\exception\order;

use exception\BaseException;
use exception\HttpCode;

class OrderException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => 'Order relative exception!',
        'errcode' => 160000,
    ];

    protected $errcode = [
        160001 => [HttpCode::SC_OK, '买家没有足够的积分！'],
        160002 => [HttpCode::SC_INTERNAL_SERVER_ERROR, '订单生成失败，未知错误！'],
        160003 => [HttpCode::SC_FORBIDDEN, '检测到用户非商品订单购买者或售卖者！'],
    ];
}