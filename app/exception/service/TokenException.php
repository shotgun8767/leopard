<?php

namespace app\exception\service;

use exception\HttpCode;

class TokenException extends ServiceException
{
    public $exception = [
        'httpCode' => HttpCode::SC_UNAUTHORIZED,
        'message' => 'Token relative exception!',
        'errcode' => 31000,
    ];

    protected $errcode = [
        '31001' => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'Fail to generate token! invalid header!'],
        '31002' => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'Fail to generate token! invalid payload!'],
        '31003' => [HttpCode::SC_UNAUTHORIZED, 'Payload of token seems to be modified! Token given is unauthorized!'],
        '31004' => [HttpCode::SC_UNAUTHORIZED, 'token is missing! please put token in header and access again!'],
        '31005' => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'Fail to generate token!'],
        '31006' => [HttpCode::SC_UNAUTHORIZED, 'token is expired! please apply for a new token!'],
        '31007' => [HttpCode::SC_INTERNAL_SERVER_ERROR, 'fail to get token object!'],
        '31008' => [HttpCode::SC_UNAUTHORIZED, 'token given is not valid!'],
        '31009' => [HttpCode::SC_UNAUTHORIZED, "token does not contain session_key, this operation is not allowed!"],
        '31010' => [HttpCode::SC_UNAUTHORIZED, "Wrong developer password!"],
        '31011' => [HttpCode::SC_UNAUTHORIZED, "You do not reach the least privilege!"]
    ];
}