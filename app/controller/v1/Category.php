<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\exception\CategoryException;
use app\model\{Category as model, Image};

class Category extends BaseApi
{
    /**
     * 获取全部种类信息
     * @return Package
     */
    public function getAll()
    {
        $info = (new model)->getAll();

        return $info ?
            Package::ok('成功获取全部种类信息', $info) :
            Package::error(CategoryException::class, 180001);
    }

    /**
     * 添加种类
     * @param $name
     * @param int $listorder
     * @return Package
     */
    public function add($name, $listorder = 0)
    {
        $Model = new model;
        if ($Model->existName($name)) {
            return Package::error(CategoryException::class, 180003);
        }

        $imageId = (new Image())->upload('image');
        if (!$imageId) {
            throw new CategoryException(180002);
        }

        (new model)->add($name, $imageId, $listorder);

        return Package::ok('成功添加种类');
    }
}