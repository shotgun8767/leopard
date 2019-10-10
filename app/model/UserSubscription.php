<?php

namespace app\model;

use model\BaseModel;

class UserSubscription extends BaseModel
{
    protected $with = [
        'getSubscription' => [
            'UserInfo' => [
                'field' => ['name', 'avatar_url']
            ]
        ]
    ];

    protected $hidden = ['status', 'user_id', 'avatar_url'];

    /**
     * 获取关注
     * @param $userId
     * @param $page
     * @param $listRows
     * @return array
     */
    public function getSubscription(int $userId, int $page, int $listRows)
    {
        return $this
            ->multi()
            ->page($page, $listRows)
            ->advancedWith($this->with['getSubscription'])
            ->getArray(['user_id' => $userId]);
    }


    /**
     * 添加关注
     * @param int $userId
     * @param int $foreignId
     * @return int
     */
    public function add(int $userId, int $foreignId)
    {
        return $this->inserts([
            'user_id' => $userId,
            'foreign_id' => $foreignId
        ], true);
    }

    /**
     * 取消关注
     * @param int $userId
     * @param int $foreignId
     * @return int
     */
    public function cancel(int $userId, int $foreignId)
    {
        return $this->destroys([
            'user_id' => $userId,
            'foreign_id' => $foreignId,
        ]);
    }

    /**
     * 是否关注
     * @param int $userId
     * @param int $foreignId
     * @return bool
     */
    public function isSubs(int $userId, int $foreignId) : bool
    {
        return $this
            ->removeOption()
            ->get([
            'user_id' => $userId,
            'foreign_id' => $foreignId
        ]) ? true : false;
    }

    public function UserInfo()
    {
        return $this->belongsTo('User', 'foreign_id', 'id');

    }
}