<?php

namespace app\model;

use model\BaseModel;

class UserPrize extends BaseModel
{
    protected $hidden = ['status', 'user_id', 'sub_activity_prize_id'];

    /**
     * 添加
     * @param int $userId
     * @param int $subActivityId
     * @param int $subActivityPrizeId
     * @return int
     */
    public function add(int $userId, int $subActivityId, int $subActivityPrizeId)
    {
        return $this
            ->status('NORMAL')
            ->inserts([
            'user_id' => $userId,
            'sub_activity_id' => $subActivityId,
            'sub_activity_prize_id' => $subActivityPrizeId
        ], true);
    }

    /**
     * 获取用户所有奖品
     * @param int $userId
     * @param int $status
     * @return array|null
     */
    public function getUserPrizes(int $userId, int $status)
    {
        $with = ['PrizeInfo' => function ($query) {
            $query->field(['id', 'name', 'detail', 'image_id']);
            $query->with(['imageInfo']);
        }];

        return $this
            ->multi()
            ->advancedWith($with)
            ->status($status)
            ->getArray([
                'user_id' => $userId
            ]);
    }

    /**
     * 获取用户收到的奖品
     * @param $id
     * @return int
     */
    public function UserReceive($id)
    {
        if ($this->getField('status', $id, true) == 3) {
            return false;
        }

        return $this->updateStatus($id, 'RECEIVED');
    }

    /**
     * 用户的奖品已开始配送
     * @param $id
     * @return bool|int
     * @throws \app\exception\DatabaseException
     * @throws \think\Exception
     */
    public function PrizeSend($id)
    {
        if ($this->getField('status', $id, true) != 1) {
            return false;
        }
        return $this->updateStatus($id, 'SEND');
    }

    /**
     * 获取开奖结果
     * @param int $subActivityId
     * @return array
     */
    public function getResult(int $subActivityId) : array
    {
        $this->with(['PrizeInfo' => function ($query) {
            $query->with(['ImageInfo' => function ($Query) {
                $Query->field(['id', 'image_url']);
            }]);
            $query->field(['id', 'image_id', 'name', 'detail']);
        }, 'UserInfo' => function ($query) {
            $query->field(['id', 'nick_name', 'avatar_url']);
        }]);

        return $this
            ->multi()
            ->getArray(['sub_activity_id' => $subActivityId], ['id', 'sub_activity_prize_id', 'user_id']);
    }

    public function PrizeInfo()
    {
        return $this->belongsTo('SubActivityPrize', 'sub_activity_prize_id', 'id');
    }

    public function UserInfo()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }
}