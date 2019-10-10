<?php


namespace app\exception\activity;


use exception\BaseException;
use exception\HttpCode;

class SubActivityException extends BaseException
{
    public $exception = [
        'httpCode' => HttpCode::SC_INTERNAL_SERVER_ERROR,
        'message' => '活动异常！',
        'errcode' => 173000,
        'data' => []
    ];

    protected $errcode = [
        173001 => [HttpCode::SC_NOT_FOUND, '本活动没有任何子活动！'],
        173002 => [HttpCode::SC_INTERNAL_SERVER_ERROR, '新添子活动失败！请检查活动开始和结束时间'],
        173003 => [HttpCode::SC_INTERNAL_SERVER_ERROR, '用户报名子活动失败！'],
        173004 => [HttpCode::SC_INTERNAL_SERVER_ERROR, '用户取消参加子活动失败！'],
        173005 => [HttpCode::SC_NOT_FOUND, '该子活动暂无用户报名！'],
        173006 => [HttpCode::SC_NOT_FOUND, '该用户没有任何报名子活动！'],
        173007 => [HttpCode::SC_NOT_FOUND, '子活动不存在！'],
        173008 => [HttpCode::SC_NOT_FOUND, '子活动已开奖！'],
        173009 => [HttpCode::SC_OK, '子活动开奖结果未公布！'],
    ];
}