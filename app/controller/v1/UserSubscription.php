<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\exception\UserSubscriptionException;
use app\model\UserSubscription as model;

class UserSubscription extends BaseApi
{
    /**
     * 获取用户关注列表
     * @return Package
     */
    public function getUserSubscription()
    {
        $userId = $this->getTokenPayload('id');

        $info = (new model)->getSubscription($userId, $this->page(), $this->row());

        return $info ?
            Package::ok('成功获取用户关注列表', $info) :
            Package::error(UserSubscriptionException::class, 150001);
    }

    /**
     * 关注用户
     * @param $foreignId
     * @return Package
     */
    public function add($foreignId)
    {
        $userId = $this->getTokenPayload('id');
        (new model)->add($userId, $foreignId);

        return Package::created('成功关注用户');
    }

    /**
     * 取消关注用户
     * @param $foreignId
     * @return Package
     */
    public function cancel($foreignId)
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->cancel($userId, $foreignId);

        return $res ?
            Package::ok() :
            Package::error(UserSubscriptionException::class, 150001);
    }
}