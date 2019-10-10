<?php

namespace app\exception;

use exception\BaseException;
use exception\HttpCode;

class UserCollectionException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => '用户收藏相关处理异常！',
        'errcode' => 190000,
    ];

    protected $errcode = [
        190001 => [HttpCode::SC_OK, '用户已收藏，无需重复收藏！'],
        190002 => [HttpCode::SC_FORBIDDEN, '用户未收藏！'],
        190003 => [HttpCode::SC_NOT_FOUND, '用户没有收藏记录！'],
    ];
}