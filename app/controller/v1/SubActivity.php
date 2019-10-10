<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\exception\activity\SubActivityException;
use app\model\{SubActivity as model, UserSubActivity, UserPrize};
use utility\general\Now;

class SubActivity extends BaseApi
{
    /**添加子活动
     * @param int $activityId
     * @return Package
     */
    public function add(int $activityId)
    {
        $res = (new model)->add($activityId, $this->param());

        return $res ?
            Package::ok('成功添加子活动') :
            Package::error(SubActivityException::class, 173002);
    }

    /**
     * 获取全部子活动
     * @param int $activityId
     * @return Package
     */
    public function getAll(int $activityId)
    {
        $res = (new model)->getAllFrom($activityId);

        return $res ?
            Package::ok('成功获取全部子活动', $res) :
            Package::error(SubActivityException::class, 173001);
    }

    /**
     * 报名参加子活动
     * @param int $subActivityId
     * @return Package
     * @throws \app\exception\activity\UserSubActivityException
     */
    public function participate(int $subActivityId)
    {
        $userId = $this->getTokenPayload('id');

        $res = (new UserSubActivity)->participate($userId, $subActivityId, $this->param());

        return $res ?
            Package::created('用户成功报名参加子活动') :
            Package::error(SubActivityException::class, 173003);
    }

    /**
     * 用户取消报名
     * @param int $subActivityId
     * @return Package
     */
    public function cancel(int $subActivityId)
    {
        $userId = $this->getTokenPayload('id');

        $res = (new UserSubActivity)->cancel($userId, $subActivityId);

        return $res ?
            Package::ok('用户成功取消参加子活动') :
            Package::error(SubActivityException::class, 173004);
    }

    /**
     * 获取子活动的全部报名用户
     * @param int $subActivityId
     * @return Package
     * @throws \ReflectionException
     */
    public function getUsers(int $subActivityId)
    {
        $usersInfo = (new UserSubActivity)->getAll($subActivityId);

        return $usersInfo ?
            Package::ok('成功获取子活动的全部报名用户', $usersInfo) :
            Package::error(SubActivityException::class, 173005);
    }

    /**
     * 获取用户参加的子活动
     * @return Package
     * @throws \ReflectionException
     */
    public function getUsersSubActivity()
    {
        $userId = $this->getTokenPayload('id');
        $res = (new UserSubActivity)->getUsersSubActivity($userId);

        return $res ?
            Package::ok('成功获取与用户报名的全部子活动', $res) :
            Package::error(SubActivityException::class, 173006);
    }

    /**
     * 获取报名用户数量
     * @param int $subActivityId
     * @return Package
     * @throws \ReflectionException
     */
    public function getUserCount(int $subActivityId)
    {
        $num = (new UserSubActivity)->getUserCount($subActivityId);

        return Package::ok('成功获取子活动报名人数', ['num' => $num]);
    }

    /**
     * 获取开奖结果
     * @param int $subActivityId
     * @return Package
     */
    public function getResult(int $subActivityId)
    {
        $res = (new model)
            ->where(['id' => $subActivityId])
            ->field('status')
            ->find();

        if (!$res) {
            return Package::error(SubActivityException::class, 173007);
        }

        $status = $res->toArray()['status'];

        if (2 === $status) {
            // 已开奖
            $res = (new UserPrize)->getResult($subActivityId);
            return Package::ok('成功获取开奖结果', $res);
        } else {
            // 未开奖
            return Package::error(SubActivityException::class, 173009);
        }
    }
}