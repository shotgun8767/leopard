<?php

namespace app\model;

use model\BaseModel;
use utility\general\Time;

/**
 * 子活动
 * Class SubActivity
 * @package app\model
 */
class SubActivity extends BaseModel
{
    /**
     * 新建子活动
     * @param int $activityId
     * @param array $data
     * @return int id
     */
    public function add(int $activityId, array $data)
    {
        $data['activity_id'] = $activityId;

        $start_time = $data['start_time'];
        $end_time = $data['end_time'];

        if (!$start_time || !$end_time || $end_time < $start_time) return false;

        return $this
            ->inserts($data);
    }

    /**
     * 获取某活动下的全部子活动
     * @param int $activityId
     * @return array|null
     */
    public function getAllFrom(int $activityId)
    {
        return $this
            ->multi()
            ->order(['listorder' => 'DESC', 'id' => 'DESC'])
            ->advancedWith('ImageInfo')
            ->getArray(['activity_id' => $activityId],
            ['id', 'name', 'detail', 'enter_start_time', 'enter_end_time', 'start_time', 'end_time', 'capacity', 'image_id']);
    }

    public function getEndTime(int $subActivityId)
    {
        return $this->getField('end_time', $subActivityId, true);
    }

    public function getEnterStartTimeAttr($time)
    {
        return $time ? Time::instance($time)->toString() : 0;
    }

    public function getEnterEndTimeAttr($time)
    {
        return $time ? Time::instance($time)->toString() : 0;
    }

    public function getStartTimeAttr($time)
    {
        return Time::instance($time)->toString();
    }

    public function getEndTimeAttr($time)
    {
        return Time::instance($time)->toString();
    }

    public function ActivityInfo()
    {
        return $this->belongsTo('activity', 'activity_id', 'id');
    }

    public function ImageInfo()
    {
        return $this->belongsTo('image', 'image_id', 'id');
    }
}