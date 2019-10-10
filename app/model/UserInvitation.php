<?php

namespace app\model;

use model\BaseModel;

class UserInvitation extends BaseModel
{
    protected $hidden = ['status'];

    /**
     * 添加一条邀请数据
     * @param $inviterId
     * @param $foreignId
     * @return bool|int
     * @throws \app\exception\DatabaseException
     * @throws \think\Exception
     */
    public function add($inviterId, $foreignId)
    {
        if ($this->get([
          'foreign_id' => $foreignId
        ])) {
            return false;
        }

        return $this->inserts([
            'inviter_id' => $inviterId,
            'foreign_id' => $foreignId,
            'finish_time' => time()
        ]);
    }
}