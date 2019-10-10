<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\model\UserLike as model;

class UserLike extends BaseApi
{
    /**
     * 客户端用户为商品点赞
     * @param $goodsId
     * @return Package
     */
    public function add($goodsId)
    {
        $userId = $this->getTokenPayload('id');

        (new model)->add($userId, $goodsId);
        return Package::created('成功点赞商品');
    }

    /**
     * 客户端用户取消商品点赞
     * @param $goodsId
     * @return Package
     */
    public function cancel($goodsId)
    {
        $userId = $this->getTokenPayload('id');

        (new model)->cancel($userId, $goodsId);
        return Package::ok('成功取消点赞商品');
    }

    /**
     * 获取商品点赞数
     * @param $goodsId
     * @return Package
     */
    public function getSumOfGoods($goodsId)
    {
        $count = (new model)->getLikeOfGoods($goodsId);

        return Package::ok('成功获取商品点赞数', ['like' => $count]);
    }
}