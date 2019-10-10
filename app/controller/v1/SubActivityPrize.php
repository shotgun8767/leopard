<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\exception\activity\SubActivityPrizeException;
use app\model\{SubActivityPrize as model, Image};
use service\Thumb;

class SubActivityPrize extends BaseApi
{
    /**
     * 获取活动奖品
     * @param $subActivityId
     * @return Package
     */
    public function getPrizes(int $subActivityId)
    {
        $info = (new model)->getPrizeOfSubActivity($subActivityId, $this->page(), $this->row());

        return $info ?
            Package::ok('成功获取活动奖品信息', $info) :
            Package::error(SubActivityPrizeException::class, 171001);
    }

    /**
     * 添加奖品
     * @param $subActivityId
     * @return Package
     * @throws SubActivityPrizeException
     */
    public function add($subActivityId)
    {
        $Image = new Image;
        $imageId = $Image->upload('image');
        if (!$imageId) {
            throw new SubActivityPrizeException(171002);
        }
        $imageId = $Image->thumb($imageId, [Thumb::SQUARE_STANDARD])[0]['thumb_id'];

        (new model)->add(
            $subActivityId,
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
     * @param $subActivityId
     * @param $id
     * @return Package
     */
    public function put($subActivityId, $id)
    {
        $res = (new model)->edit($id, $this->param());

        return $res ?
            Package::ok('成功修改奖品信息') :
            Package::error(SubActivityPrizeException::class, 171003);
    }
}