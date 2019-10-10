<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 2019/5/13
 * Time: 0:36
 */

namespace app\exception;

use exception\{BaseException, HttpCode};

class UserException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => '用户相关处理异常！',
        'errcode' => 100000,
    ];

    protected $errcode = [
        100001 => [HttpCode::SC_NOT_FOUND, '用户不存在！'],
        100003 => [HttpCode::SC_FORBIDDEN, '检测到邀请用户和受邀请用户为同一人！'],
        100004 => [HttpCode::SC_INTERNAL_SERVER_ERROR, '客户端用户已操作邀请码！'],
        100005 => [HttpCode::SC_NOT_FOUND, '没有找到拥有此邀请码的用户！'],
        100006 => [HttpCode::SC_NOT_FOUND, '获取用户信息失败！'],
        100007 => [HttpCode::SC_OK, '本次操作没有修改任何用户信息。'],
        100008 => [HttpCode::SC_OK, '用户本日已签到！'],
        100009 => [HttpCode::SC_BAD_REQUEST, '邀请码不符合规范！'],
    ];
}