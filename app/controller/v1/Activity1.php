<?php

namespace app\controller\v1;

use api\BaseApi;
use api\Package;
use app\exception\activity\SubActivityException;
use app\model\{UserPrize, UserSubActivity, SubActivityPrize, SubActivity};
use utility\general\{Now, Random};

class Activity1 extends BaseApi
{
    public function draw(int $subActivityId)
    {
        $SubActivity = new SubActivity;
        $info = $SubActivity->statusAll()->get($subActivityId, ['status', 'end_time']);
        $status = $info['status']??null;
        if (1 == $status) {
            // 更新子活动状态，已开奖
            //$SubActivity->updateStatus($subActivityId,'DRAW');
        } elseif(2 == $status) {
            throw new SubActivityException(173008);
        } else {
            throw new SubActivityException(173007);
        }


        # 在关闭连接后，继续运行php脚本
        ignore_user_abort(true);

        # 计算时间差值
        $endTime = $info->getOrigin('end_time');
        if (Now::before($endTime)) {
            set_time_limit(0);
            sleep($endTime - time());
        }

        # 获取子活动的全部用户
        $users = (new UserSubActivity)->getUserColumn($subActivityId);

        # 获取奖品
        $prizes = (new SubActivityPrize)
            ->multi()
            ->getArray(['sub_activity_id' => $subActivityId], ['id', 'count']);

        # 参与用户随机池
        $Random = (new Random)->put($users);

        $UserPrize = new UserPrize;

        foreach ($prizes as $prize) {
            $prizeId = $prize['id'];
            $drawUsers = $Random->get($prize['count']);
            $Random->remove($drawUsers);
            foreach ($drawUsers as $drawUser) {
                $UserPrize->add($drawUser, $subActivityId, $prizeId);
            }
        }

        return Package::ok('成功完成抽奖');
    }
}