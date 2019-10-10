<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\model\Order as model;
use app\exception\order\OrderException;

class Order extends BaseApi
{
    /**
     * 生成订单
     * @return Package
     */
    public function newOrder()
    {
        $userId = $this->getTokenPayload('id');

        $res = (new model)->new(
            $this->param('price'),
            $this->param('count'),
            $userId,
            $this->param('seller_id')
        );

        switch ($res) {
            case -1 :
                return Package::error(OrderException::class, 160001);
            case -2 :
                return Package::error(OrderException::class, 160001);
            default :
                return Package::created('成功生成订单', $res);
        }
    }
}