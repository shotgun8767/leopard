<?php

namespace app\controller\v1;

use api\BaseApi;
use api\Package;
use app\exception\UserCollectionException;
use app\model\{UserCollection as model};

class UserCollection extends BaseApi
{
    /**
     * 用户添加收藏
     * @param int $goodsId
     * @return Package
     */
    public function add(int $goodsId)
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->add($userId, $goodsId);

        return $res ?
            Package::ok('成功添加收藏') :
            Package::error(UserCollectionException::class, 190001);
    }

    /**
     * 用户取消收藏
     * @param int $goodsId
     * @return Package
     */
    public function cancel(int $goodsId)
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->cancel($userId, $goodsId);

        return $res ?
            Package::ok('成功取消收藏') :
            Package::error(UserCollectionException::class, 190002);
    }

    public function getInfo(int $collectionId)
    {

    }

    public function getAll()
    {
        $userId = $this->getTokenPayload('id');
        $info = (new model)->getAll($userId, $this->page(), $this->row());

        return $info ?
            Package::ok('成功获取用户全部收藏记录', $info) :
            Package::error(UserCollectionException::class, 190003);
    }
}