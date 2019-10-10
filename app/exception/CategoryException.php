<?php

namespace app\exception;

use exception\BaseException;
use exception\HttpCode;

class CategoryException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => 'Database relative exception!',
        'errcode' => 180000,
    ];

    protected $errcode = [
        180001 => [HttpCode::SC_NOT_FOUND, '获取种类信息失败，未找到资源！'],
        180002 => [HttpCode::SC_BAD_REQUEST, '请求中缺少图片image！'],
        180003 => [HttpCode::SC_FORBIDDEN, '拒绝添加种类！已有同名种类！'],
    ];
}