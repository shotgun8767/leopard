<?php

use api\route\{Restful, Param, Route};

$routes = [
    Restful::post('添加商品订单')
        ->route('OrderGoods@newOrderGoods')
        ->token(1)
        ->post([
            'goods_id' => Param::int()->desc('商品id')->require(true),
            'count' => Param::int(6)->desc('数量')->setDefault(1),
            'remark' => Param::string()->desc('备注')->setDefault(''),
            'address' => Param::string()->desc('收货地址')->require(true)
        ]),

    Restful::put('买家更新订单状态', 'buyer')
        ->route('OrderGoods@updateBuyerAction')
        ->desc('【买家订单状态】0: 默认, 1: 已收货。当买家状态更新至1时，卖家将获得相应的积分。')
        ->token(1)
        ->post([
            'order_goods_id' => Param::int()->desc('商品订单id')->require(true),
            'action' => Param::tinyint(1)->desc('买家的订单状态')->require(true)
        ]),

    Restful::put('卖家更新订单状态', 'seller')
        ->route('OrderGoods@updateSellerAction')
        ->desc('【卖家订单状态】0: 未接单, 1: 已接单, 2: 存货不足，未接单, 3: 已拒绝, 4: 商品已送达。当卖家状态更新至3时，买家获取退还积分。')
        ->token(1)
        ->post([
            'order_goods_id' => Param::int()->desc('商品订单id')->require(true),
            'action' => Param::tinyint(1)->desc('卖家的订单状态')->require(true)
        ]),

    Restful::get('获取买家购买订单记录', 'buyer')
        ->route('OrderGoods@getBuyerOrderGoods')
        ->paginate(true)
        ->param([
            'seller_action' => Param::int()->desc('商品订单卖家操作状态'),
            'buyer_action' => Param::int()->desc('商品订单买家操作状态')
        ])
        ->token(1)
        ->dataMode(Route::DATA_MODE_MULTI),

    Restful::get('获取卖家购买订单记录', 'seller')
        ->route('OrderGoods@getSellerOrderGoods')
        ->paginate(true)
        ->param([
            'seller_action' => Param::int()->desc('商品订单卖家操作状态'),
            'buyer_action' => Param::int()->desc('商品订单买家操作状态')
        ])
        ->token(1)
        ->dataMode(Route::DATA_MODE_MULTI),

    Restful::get('获取商品订单详情', ':orderGoodsId')
        ->rulePattern([
            'orderGoodsId' => '@id'
        ])
        ->param([
            'orderGoodsId' => Param::int()->require(true)->desc('商品订单id')
        ])
        ->desc('自动判断用户是买家还是卖家，返回响应的信息')
        ->token(1)
        ->route('OrderGoods@GetOrderGoodsDetail')
];

Restful::group('order/goods', $routes);