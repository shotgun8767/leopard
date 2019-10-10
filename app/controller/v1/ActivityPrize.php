<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\exception\activity\ActivityPrizeException;
use app\model\{ActivityPrize as model, Image};
use service\Thumb;

class ActivityPrize extends BaseApi
{
    /**
     * 获取活动奖品
     * @param $activityId
     * @return Package
     */
    public function getPrizes($activityId)
    {
        $info = (new model)->getPrizeOfActivity($activityId, $this->page(), $this->row());

        return $info ?
            Package::ok('成功获取活动奖品信息', $info) :
            Package::error(ActivityPrizeException::class, 171001);
    }

    /**
     * 添加奖品
     * @param $activityId
     * @return Package
     * @throws ActivityPrizeException
     */
    public function add($activityId)
    {
        $Image = new Image;
        $imageId = $Image->upload('image');
        if (!$imageId) {
            throw new ActivityPrizeException(171002);
        }
        $imageId = $Image->thumb($imageId, [Thumb::SQUARE_STANDARD])[0]['thumb_id'];

        (new model)->add(
            $activityId,
            $imageId,
            $this->param('name'),
            $this->param('detail'),
            $this->param('lottery_time'),
            $this->param('count'),
            $this->param('listorder')
        );

        return Package::created('成功添加活动奖品');
    }

    /**
     * 修改奖品信息
     * @param $activityId
     * @param $id
     * @return Package
     */
    public function put($activityId, $id)
    {
        $res = (new model)->put($activityId, $id, $this->param());

        return $res ?
            Package::ok('成功修改奖品信息') :
            Package::error(ActivityPrizeException::class, 171003);
    }
}