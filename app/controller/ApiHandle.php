<?php

namespace app\controller;

use api\{ApiCore, BaseApi, Package};
use app\exception\RouteException;
use exception\BaseException;

class ApiHandle extends BaseApi
{
    /**
     * api核心
     * @var ApiCore
     */
    public static $apiCore;

    /**
     * Api 处理入口
     * @throws BaseException
     */
    public function index() : void
    {
        $Package = self::$apiCore->callRoute();

        if ($Package instanceof Package) {
            $Package->throw();
        } else {
            Package::noContent()->throw();
        }
    }

    /**
     * 获取api核心
     * @param int $type
     * @return ApiCore
     */
    public static function apiCore(int $type = ApiCore::RESTFUL) : ApiCore
    {
        if (is_null(self::$apiCore)) {
            self::$apiCore = ApiCore::new($type);
        }
        return self::$apiCore;
    }

    /**
     * 处理路由不存在的情况
     * @throws RouteException
     */
    public function missRule()
    {
        throw new RouteException(40001);
    }
}