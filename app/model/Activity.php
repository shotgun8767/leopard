<?php

namespace app\model;

use model\BaseModel;

/**
 * 活动
 * Class Activity
 * @package app\model
 */
class Activity extends BaseModel
{
    protected $fields = [
        'getInfo_detail' => ['id', 'name', 'detail', 'image_id'],
        'getInfo' => ['image_id']
    ];

    protected $hidden = ['image_id'];

    /**
     * 创建一个活动
     * @param string $name
     * @param string $detail
     * @param int $imageId
     * @param int $listorder
     * @param int $start_time
     * @param int $end_time
     * @return int
     */
    public function new(
        string $name,
        string $detail,
        int $imageId,
        int $listorder,
        int $start_time = 0,
        int $end_time = 0
    ) {
        return $this->inserts([
            'name' => $name,
            'description' => $detail,
            'image_id' => $imageId,
            'listorder' => $listorder,
            'start_time' => $start_time,
            'end_time' => $end_time
        ]);
    }

    /**
     * 获取活动信息
     * @param $id
     * @param $detail
     * @return array|null
     */
    public function getInfo(int $id, bool $detail)
    {
        $with = [
            'ImageInfo' => [
                'field' => ['image_url']
            ]
        ];
        return $this
            ->advancedWith($with)
            ->getArray($id, $detail ? 'getInfo_detail' : 'getInfo');
    }

    /**
     * 获取全部进行中的活动
     * @return array|null
     */
    public function getAll()
    {
        $with = [
            'ImageInfo' => [
                'field' => ['image_url']
            ]
        ];

        return $this
            ->multi()
            ->advancedWith($with)
            ->getArray([], ['id', 'image_id']);
    }

    public function ImageInfo()
    {
        return $this->belongsTo('image', 'image_id', 'id');
    }
}