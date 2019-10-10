<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\exception\activity\ActivityUserException;
use app\model\ActivityUser as model;

class ActivityUser extends BaseApi
{
    /**
     * 用户报名活动
     * @param $activityId
     * @return Package
     */
    public function participate($activityId)
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->participate($userId, $activityId);

        return $res ?
            Package::created('成功报名活动') :
            Package::error(ActivityUserException::class, 172001);
    }

    /**
     * 用户取消报名活动
     * @param $activityId
     * @return Package
     */
    public function cancel($activityId)
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->cancel($userId, $activityId);

        return $res ?
            Package::created('报名活动失败') :
            Package::error(ActivityUserException::class, 172002);
    }

    /**
     * 获取活动的参与用户
     * @param $activityId
     * @return Package
     */
    public function getUserOfActivity($activityId)
    {
        $res = (new model)->getUsersOfActivity($activityId, $this->page(), $this->row());

        if (false === $res) {
            return Package::error(ActivityUserException::class, 172003);
        }

        return $res ?
            Package::ok('成功获取活动的参与用户') :
            Package::ok('该活动暂无参与用户！');
    }

    /**
     * 获取用户报名活动
     * @return Package
     */
    public function getUserActivity()
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->getUserActivity($userId, $this->page(), $this->row());

        return $res ?
            Package::ok('成功获取用户报名活动') :
            Package::ok('用户没有报名任何活动！');
    }
}