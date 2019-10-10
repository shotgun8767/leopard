<?php

namespace app\model;

use app\exception\activity\UserSubActivityException;
use model\BaseModel;
use think\db\Query;
use utility\general\Now;
use utility\general\Time;

class UserSubActivity extends BaseModel
{
    protected $hidden = ['status', 'listorder'];

    /**
     * 报名参加活动
     * @param int $userId
     * @param int $subActivityId
     * @param array $data
     * @return int
     */
    public function participate(int $userId, int $subActivityId, array $data)
    {
        $model = (new SubActivity)->get($subActivityId);

        if (!$model) {
            throw new UserSubActivityException(172001);
        }
        if ($this->get(['user_id' => $userId, 'subActivityId' => $subActivityId])) {
            throw new UserSubActivityException(172002);
        }

        $capacity = $model->getOrigin('capacity');
        $count = $this->getCount(['subActivityId' => $subActivityId]);

        # 子活动报名人数已满
        if ($capacity && $capacity <= $count) {
            throw new UserSubActivityException(172003);
        }

        $enter_start_time = $model->getOrigin('enter_start_time');
        $enter_end_time = $model->getOrigin('enter_end_time');
        $start_time = $model->getOrigin('start_time');
        $now = Time::now();

        if ($enter_start_time != 0 && $now->before($enter_start_time)) {
            throw new UserSubActivityException(172004);
        }
        if ($enter_end_time != 0 && $now->after($enter_end_time)) {
            throw new UserSubActivityException(172005);
        }
        if ($now->after($start_time)) {
            throw new UserSubActivityException(172006);
        }

        // 成功报名
        $data['user_id'] = $userId;
        $data['sub_activity_id'] = $subActivityId;
        return $this->inserts($data);
    }

    /**
     * 取消报名子活动
     * @param int $userId
     * @param int $subActivityId
     * @return int
     */
    public function cancel(int $userId, int $subActivityId)
    {
        // 如果报名结束，则不能取消报名
        $model = (new SubActivity)->get($subActivityId);

        if (!$model) {
            throw new UserSubActivityException(172001);
        }

        // 子活动报名结束，不允许取消报名
        $enter_end_time = $model->getOrigin('enter_end_time');
        if ($enter_end_time != 0 && Now::after($enter_end_time)) {
            throw new UserSubActivityException(172005);
        }

        return $this->softDelete([
            'user_id' => $userId,
            'sub_activity_id' => $subActivityId
        ]);
    }

    /**
     * 获取子活动的全部报名用户
     * @param int $subActivityId
     * @return array|null
     */
    public function getAll(int $subActivityId)
    {
        $with = [
            'UserInfo' => [
                'field' => ['name', 'avatar_url']
            ]
        ];

        return $this
            ->multi()
            ->advancedWith($with)
            ->getArray([
                'sub_activity_id' => $subActivityId
            ], ['user_id']);
    }

    /**
     * 获取用户参加的子活动
     * @param int $userId
     * @return array|null
     */
    public function getUsersSubActivity(int $userId)
    {
        $this->with(['subActivityInfo' => function ($query) {
                $query->field(['id', 'activity_id', 'name', 'detail', 'start_time', 'end_time', 'image_id']);
                $query->with(['ActivityInfo' => function ($Query) {
                    $Query->field(['id', 'name']);
                }, 'imageInfo' => function ($Query) {
                    $Query->field(['id', 'image_url']);
                }]);
            }]);

        return $this
            ->multi()
            ->order(['id' => 'desc'])
            ->getArray(['user_id' => $userId], ['sub_activity_id']);
    }

    public function getUserCount(int $subActivityId)
    {
        return $this->getCount(['sub_activity_id' => $subActivityId]);
    }

    public function UserInfo()
    {
        return $this->belongsTo('user', 'user_id', 'id');
    }

    public function getUserColumn(int $subActivityId)
    {
        return $this->getColumn('user_id', ['sub_activity_id' => $subActivityId]);
    }

    public function subActivityInfo()
    {
        return $this->belongsTo('sub_activity', 'sub_activity_id', 'id');
    }
}