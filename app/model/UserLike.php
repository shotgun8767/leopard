<?php

namespace app\model;

use model\BaseModel;

class UserLike extends BaseModel
{
    protected $hidden = ['status', 'goods_id'];

    /**
     * 点赞
     * @param int $userId
     * @param int $goodsId
     * @return int
     */
    public function add(int $userId, int $goodsId)
    {
        return $this->inserts([
            'user_id' => $userId,
            'goods_id' => $goodsId
        ], true);
    }

    /**
     * 取消点赞
     * @param int $userId
     * @param int $goodsId
     * @return int
     */
    public function cancel(int $userId, int $goodsId)
    {
        return $this->softDelete([
            'user_id' => $userId,
            'goods_id' => $goodsId
        ]);
    }

    /**
     * 获取商品的点赞数
     * @param int $goodsId
     * @return int
     */
    public function getLikeOfGoods(int $goodsId)
    {
        return $this->getCount(['goods_id' => $goodsId]);
    }
}