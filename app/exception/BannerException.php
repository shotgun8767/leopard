<?php

namespace app\exception;

use exception\BaseException;
use exception\HttpCode;

class BannerException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => 'Database relative exception!',
        'errcode' => 130000,
    ];

    protected $errcode = [
        130001 => [HttpCode::SC_NOT_FOUND, '未找到轮播图'],
        130002 => [HttpCode::SC_INTERNAL_SERVER_ERROR, '添加轮播图失败'],
    ];
}