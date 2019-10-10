<?php

use api\route\{Restful, Param};

$routes = [
    Restful::post('生成订单', 'new')
        ->route('Order@newOrder')
        ->desc('生成一个订单，同时买家扣除相应的积分，但卖家的积分不发生变化。')
        ->token(1)
        ->post([
            'price' => Param::int()
                ->desc('单价')
                ->require(true),
            'count' => Param::int(6)
                ->desc('数量')
                ->setDefault(1),
            'seller_id' => Param::int()
                ->desc('商家id')
                ->require(true)
        ])
        ->returnDesc([
            'order_no'      => ['int', '订单号'],
            'buyer_id'      => ['int', '买家id'],
            'seller_id'     => ['int', '卖家id'],
            'buyer_balance' => ['int', '买家余额']
        ])
];

Restful::group('order', $routes);