<?php

namespace app\model;

use model\BaseModel;

class ActivityUser extends BaseModel
{
    protected $hidden = ['status'];

    /**
     * 用户报名活动
     * @param int $userId
     * @param int $subActivityId
     * @return int
     */
    public function participate(int $userId, int $subActivityId)
    {
        return $this->inserts([
            'user_id' => $userId,
            'sub_activity_id' => $subActivityId
        ], true);
    }

    /**
     * 用户取消报名
     * @param int $userId
     * @param int $subActivityId
     * @return int
     */
    public function cancel(int $userId, int $subActivityId)
    {
        return $this->destroys([
            'user_id' => $userId,
            'sub_activity_id' => $subActivityId
        ]);
    }

    /**
     * 获取活动参与者
     * @param int $subActivityId
     * @param int $page
     * @param int $listRows
     * @return array|bool|\think\Collection|\think\Model|\think\model\Collection|null
     */
    public function getUsersOfActivity(int $subActivityId, int $page, int $listRows)
    {
        if (!(new Activity)->get($subActivityId)) {
            return false;
        }

        $with = [
            'UserInfo' => [
                'field' => ['name', 'avatar_url'],
                'hidden' => 'user_id'
            ],
        ];

        return $this
            ->multi()
            ->page($page, $listRows)
            ->advancedWith($with)
            ->get(['sub_activity_id' => $subActivityId], ['hidden', 'sub_activity_id']);
    }

    public function getUsersIdOfActivity(int $subActivityId)
    {
        return $this->getColumn('user_id', ['sub_activity_id' => $subActivityId]);
    }

    /**
     * 获取用户报名的活动
     * @param int $userId
     * @param int $page
     * @param int $listRows
     * @return array|\think\Collection|\think\Model|\think\model\Collection|null
     */
    public function getUserActivity(int $userId, int $page, int $listRows)
    {
        $with = [
            'ActivityInfo' => [
                'field' => [],
                'hidden' => 'sub_activity_id'
            ],
        ];

        return $this
            ->multi()
            ->page($page, $listRows)
            ->advancedWith($with)
            ->get(['user_id' => $userId], ['hidden', 'sub_activity_id']);
    }

    public function SubActivityInfo()
    {
        return $this->belongsTo('activity', 'sub_activity_id', 'id');
    }

    public function UserInfo()
    {
        return $this->belongsTo('user', 'user_id', 'id');
    }
}