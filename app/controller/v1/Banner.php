<?php

namespace app\controller\v1;

use api\BaseApi;
use api\Package;
use app\exception\BannerException;
use app\model\{Banner as model, Image};

class Banner extends BaseApi
{
    /**
     * 获取轮播图
     * @return Package
     * @throws \ReflectionException
     */
    public function getBanners()
    {
        $limit = $this->param('limit');

        $banners = (new model)->getInfo($limit);

        return $banners ?
            Package::ok('成功获取轮播图', $banners) :
            Package::error(BannerException::class, 130001);
    }


    public function upload()
    {
        $imageId = (new Image)->upload('image');
        $res = (new model)->add($imageId, $this->param('listorder', 0));

        return $res ?
            Package::ok('成功添加轮播图') :
            Package::error(BannerException::class, 130002);
    }

}