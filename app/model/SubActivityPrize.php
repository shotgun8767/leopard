<?php

namespace app\model;

use model\BaseModel;
use utility\general\Time;

/**
 * 奖品
 * Class ActivityPrize
 * @package app\model
 */
class SubActivityPrize extends BaseModel
{
    protected $hidden = ['status', 'listorder', 'image_id'];

    /**
     * 添加活动奖品
     * @param int $subActivityId
     * @param int $imageId
     * @param string $name
     * @param string $detail
     * @param int $lottery_time
     * @param int $count
     * @param int $listorder
     * @return int
     */
    public function add(
        int $subActivityId,
        int $imageId,
        string $name,
        string $detail,
        int $lottery_time,
        int $count = 1,
        int $listorder = 0
    ) {
        $data = [
            'sub_activity_id' => $subActivityId,
            'image_id' => $imageId,
            'name' => $name,
            'detail' => $detail,
            'lottery_time' => $lottery_time,
            'count' => $count,
            'listorder' => $listorder
        ];
        return $this->inserts($data);
    }

    /**
     * 修改奖品内容
     * @param int $id
     * @param array $data
     * @return int
     */
    public function edit(int $id, array $data)
    {
        return $this->updates($id, $data);
    }

    /**
     * 获取某活动的奖品
     * @param int $subActivityId
     * @param int $page max 99
     * @param $row
     * @return array|null
     */
    public function getPrizeOfSubActivity(int $subActivityId, int $page, int $row)
    {
        $with = [
            'SubActivityInfo' => [
                'field' => ['name', 'detail', 'image_id'],
            ],
            'ImageInfo' => [
                'field' => 'image_url'
            ],
        ];

        $this->order(['listorder' => 'DESC']);
        return $this
            ->multi()
            ->page($page, $row)
            ->advancedWith($with)
            ->getArray(['sub_activity_id' => $subActivityId], ['hidden', 'sub_activity_id']);
    }

    public function SubActivityInfo()
    {
        return $this->belongsTo('sub_activity', 'sub_activity_id', 'id');
    }

    public function ImageInfo()
    {
        return $this->belongsTo('image', 'image_id', 'id');
    }

    public function getLotteryTimeAttr($time)
    {
        return Time::instance($time)->toString('Y/m/d H:i:s');
    }
}