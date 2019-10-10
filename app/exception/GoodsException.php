<?php

namespace app\exception;

use exception\{BaseException, HttpCode};

class GoodsException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => '商品相关处理异常！',
        'errcode' => 140000,
    ];

    protected $errcode = [
        140001 => [HttpCode::SC_NOT_FOUND, '商品不存在！'],
        140002 => [HttpCode::SC_NOT_FOUND, '已无商品推荐！'],
        140003 => [HttpCode::SC_BAD_REQUEST, '请求信息中未找到相应的图片资源！'],
        140004 => [HttpCode::SC_INTERNAL_SERVER_ERROR, '上传商品失败！'],
        140005 => [HttpCode::SC_NOT_FOUND, '商品不存在！'],
        140006 => [HttpCode::SC_UNAUTHORIZED, '修改商品信息失败！检测到客户端用户与商家并非同一人！'],
        140007 => [HttpCode::SC_UNAUTHORIZED, '商品不存在或客户端用户与商家并非同一人！'],
        140008 => [HttpCode::SC_UNAUTHORIZED, '不能删除该商品，有相关订单处理中，请先拒绝订单！'],
        140009 => [HttpCode::SC_NOT_FOUND, '没有找到更多商品了！'],
        140010 => [HttpCode::SC_NOT_FOUND, '用户暂无发布商品！'],
    ];
}