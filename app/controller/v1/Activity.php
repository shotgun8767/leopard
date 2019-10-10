<?php

namespace app\controller\v1;

use app\exception\activity\ActivityException;
use app\exception\activity\SubActivityPrizeException;
use app\model\{Activity as model, Image};
use api\{BaseApi, Package};

class Activity extends BaseApi
{
    /**
     * @return Package
     * @throws SubActivityPrizeException
     */
    public function upload()
    {
        $Image = new Image;
        $imageId = $Image->upload('image');
        if (!$imageId) {
            throw new SubActivityPrizeException(171002);
        }

        (new model)->new(
            $this->param('name'),
            $this->param('detail'),
            $imageId,
            $this->param('listorder', 0),
            $this->param('start_time'),
            $this->param('end_time')
        );
        return Package::created('成功创建活动');
    }

    /**
     * 获取活动信息
     * @param $activityId
     * @param $detail
     * @return Package
     */
    public function getInfo(int $activityId, $detail)
    {
        $info = (new model)->getInfo($activityId, $detail);

        return $info ?
            Package::ok('成功获取活动内容', $info) :
            Package::error(ActivityException::class, 170001);
    }

    /**
     * 获取所有活动
     * @return Package
     */
    public function getAll()
    {
        $info = (new model)->getAll();

        return $info ?
            Package::ok('成功获取所有活动', $info) :
            Package::error(ActivityException::class, 170003);
    }
}