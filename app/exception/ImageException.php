<?php

namespace app\exception;

use exception\{BaseException, HttpCode};

class ImageException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_BAD_GATEWAY,
        'message' => '图片相关处理异常！',
        'errcode' => 120000,
    ];

    protected $errcode = [
        120001 => [HttpCode::SC_BAD_REQUEST, '图片不存在！'],
        120002 => [HttpCode::SC_NOT_FOUND, '未找到相应的图片资源！'],
    ];
}