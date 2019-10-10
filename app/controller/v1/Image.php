<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\model\Image as model;
use app\exception\ImageException;

class Image extends BaseApi
{
    /**
     * 上传一张图片
     * @return Package
     * @throws \ReflectionException
     */
    public function upload()
    {
        $Model = new model;
        $id = $Model->upload('image');
        return false === $id ?
            Package::error(ImageException::class, 120001) :
            Package::ok('成功上传图片！', ['image_id' => $id]);
    }

    /**
     * 根据id获取图片信息
     * @param $id
     * @param bool $detail
     * @return Package
     */
    public function get($id, $detail = false)
    {
        $res = (new model)->getById($id, $detail);

        return $res ?
            Package::ok('成功获取图片信息', $res) :
            Package::error(ImageException::class, 120002);
    }

    public function uploadObj()
    {
        echo 1;
        (new model)->uploadObj();
    }
}