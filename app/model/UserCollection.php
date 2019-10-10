<?php

namespace app\model;

use model\BaseModel;

/**
 * 用户收藏
 * Class UserCollection
 * @package app\model
 */
class UserCollection extends BaseModel
{
    protected $with = [
        'getCollections' => [
            'CollectionInfo' => [
                'with' => 'ImageInfo',
                'field' => ['name', 'price', 'image_id'],
            ],
        ],
    ];

    protected $hidden = ['status', 'id', 'goods_id'];

    /**
     * 获取收藏
     * @param int $userId
     * @param int $page
     * @param int $listRows
     * @return array|null
     */
    public function getAll(int $userId, int $page, int $listRows)
    {
        return $this
            ->multi()
            ->page($page, $listRows)
            ->advancedWith($this->with['getCollections'])
            ->getArray(['user_id' => $userId,], ['id', 'goods_id']);
    }

    /**
     * 新增收藏
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
     * 获取某件商品的收藏总数
     * @param int $goodsId
     * @return int
     */
    public function getCollectionOfGoods(int $goodsId)
    {
        return $this
            ->whereBase(['goods_id' => $goodsId])
            ->count('id');
    }

    public function CollectionInfo()
    {
        return $this->belongsTo('Goods', 'goods_id', 'id');
    }
}