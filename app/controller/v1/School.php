<?php

namespace app\controller\v1;

use app\model\{School as model};
use api\{BaseApi, Package};

class School extends BaseApi
{
    /**
     * 获取所有学校
     * @return Package
     */
    public function getAll()
    {
        $info = (new model)->getAll();

        return Package::ok('成功获取所有学校', $info);
    }

    public function add()
    {
        $res = (new model)->add($this->param());

        return Package::ok('成功添加学校');
    }
}