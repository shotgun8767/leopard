<?php

namespace app\controller\v1;

use api\BaseApi;
use api\Package;
use app\model\UserPrize as model;
use app\exception\UserPrizeException;

class UserPrize extends BaseApi
{
    public function getUserPrizes(int $status)
    {
        $userId = $this->getTokenPayload('id');

        $info = (new model)->getUserPrizes($userId, $status);

        return $info ?
            Package::ok('成功获取用户所得奖品信息', $info) :
            Package::error(UserPrizeException::class, 210002);
    }

    public function PrizeSend(int $id)
    {
        $res = (new model)->prizeSend($id);
        return $res ?
            Package::ok('奖品状态为：已发送') :
            Package::error(UserPrizeException::class, 210001);
    }


    public function PrizeReceive(int $id)
    {
        $res = (new model)->UserReceive($id);

        return $res ?
            Package::ok('奖品状态为：已签收') :
            Package::error(UserPrizeException::class,210001);
    }
}