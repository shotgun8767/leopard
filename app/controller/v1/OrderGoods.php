<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\exception\order\OrderGoodsException;
use app\model\OrderGoods as model;

class OrderGoods extends BaseApi
{
    public function newOrderGoods()
    {
        $userId = $this->getTokenPayload('id');

        $Model = new model;
        $res = $Model->new(
            $userId,
            $this->param('goods_id'),
            $this->param('count'),
            $this->param('remark'),
            $this->param('address')
        );

        if (false === $res)
        {
            return Package::error(OrderGoodsException::class, 161017);
        }

        return Package::created('成功添加商品订单！', $res);
    }

    public function updateSellerAction($order_goods_id, $action)
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->updateSellerAction($userId, $order_goods_id, $action);

        return Package::ok('卖家成功更改订单状态', $res);
    }

    public function updateBuyerAction($order_goods_id, $action)
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->updateBuyerAction($userId, $order_goods_id, $action);

        return Package::ok('买家成功更改订单状态', $res);
    }

    public function getBuyerOrderGoods()
    {
        $userId = $this->getTokenPayload('id');
        $info = (new model)->getBuyerOrderGoods($userId);

        return !empty($info) ?
            Package::ok('成功获取买家购买订单记录', $info) :
            Package::error(OrderGoodsException::class, 161018);
    }

    public function getSellerOrderGoods()
    {
        $userId = $this->getTokenPayload('id');
        $info = (new model)->getSellerOrderGoods($userId);

        return !empty($info) ?
            Package::ok('成功获取卖家购买订单记录', $info) :
            Package::error(OrderGoodsException::class, 161019);
    }

    public function GetOrderGoodsDetail(int $orderGoodsId)
    {
        $userId = $this->getTokenPayload('id');

        $res = (new model)->GetOrderGoodsDetail($userId, $orderGoodsId);

        return Package::ok('成功获取信息', $res);
    }
}