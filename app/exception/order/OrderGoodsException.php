<?php

namespace app\exception\order;

use exception\BaseException;
use exception\HttpCode;

class OrderGoodsException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => 'Order relative exception!',
        'errcode' => 161000,
    ];

    protected $errcode = [
        161001 => [HttpCode::SC_FORBIDDEN, '检测到买家和卖家为同一人！'],
        161002 => [HttpCode::SC_OK, '买家没有足够的积分！'],
        161003 => [HttpCode::SC_OK, '商品订单生成失败！订单生成出错！'],
        161004 => [HttpCode::SC_OK, '商家操作无效！本操作已执行！'],
        161005 => [HttpCode::SC_UNAUTHORIZED, '检测到客户端用户非商品订单对应商家！'],
        161006 => [HttpCode::SC_NOT_FOUND, '商品订单未找到！'],
        161007 => [HttpCode::SC_NOT_FOUND, '商品订单对应的商品不存在！'],
        161008 => [HttpCode::SC_FORBIDDEN, '商品库存量不足！无法接单！'],
        161009 => [HttpCode::SC_FORBIDDEN, '不能完成该操作！订单已被拒绝或已完成！'],
        161010 => [HttpCode::SC_FORBIDDEN, '不能完成该操作！订单已完成送货！'],
        161011 => [HttpCode::SC_FORBIDDEN, '不能完成该操作！订单已完成送货或已接单！'],
        161012 => [HttpCode::SC_FORBIDDEN, '不能完成该操作！请先接受订单！'],
        161013 => [HttpCode::SC_OK, '买家操作无效！本操作已执行！'],
        161014 => [HttpCode::SC_UNAUTHORIZED, '检测到客户端用户非商品订单对应买家！'],
        161015 => [HttpCode::SC_NOT_FOUND, '商品订单未找到！'],
        161016 => [HttpCode::SC_FORBIDDEN, '不能完成该操作！商家尚未确认送货到达，或商家未处理订单！'],
        161017 => [HttpCode::SC_NOT_FOUND, '商品未找到！'],
        161018 => [HttpCode::SC_NOT_FOUND, '买家没有商品订单！'],
        161019 => [HttpCode::SC_NOT_FOUND, '卖家没有商品订单！'],
        161020 => [HttpCode::SC_NOT_FOUND, '商品已售罄！'],
    ];
}