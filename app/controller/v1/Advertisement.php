<?php

namespace app\controller\v1;

use api\BaseApi;
use api\Package;
use app\model\Advertisement as model;

class Advertisement extends BaseApi
{
    /**
     * 获取全部广告
     * @return Package
     * @throws \ReflectionException
     */
    public function getAll()
    {
        $info = (new model)->getAll();

        return Package::ok('成功获取广告信息', $info);
    }
}