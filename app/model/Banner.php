<?php

namespace app\model;

use model\BaseModel;

class Banner extends BaseModel
{
    protected $freeFields = ['listorder'];

    protected $hidden = ['image_id', 'listorder'];

    protected $with = [
        'getInfo' => [
            'ImageInfo' => [
                'field' => 'image_url'
            ]
        ]
    ];

    /**
     * 添加轮播图
     * @param int $imageId
     * @param int $listorder
     * @return int
     */
    public function add(int $imageId, int $listorder)
    {
        return $this->inserts([
            'image_id' => $imageId,
            'listorder' => $listorder
        ]);
    }

    /**
     * 获取轮播图
     * @param int $limit    数量
     * @return array|null
     */
    public function getInfo(int $limit)
    {
        $this->order([
            'listorder' => 'DESC',
            'id' => 'DESC'
        ]);

        return $this
            ->multi($limit)
            ->advancedWith($this->with['getInfo'])
            ->getArray([], ['id', 'image_id']);
    }

    public function ImageInfo()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }
}