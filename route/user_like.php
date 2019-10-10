<?php

use api\route\{Restful, Param};

$routes = [
    Restful::post('客户端用户点赞商品', ':goodsId')
        ->route('UserLike@add')
        ->token(1)
        ->rulePattern([
            'goodsId' => '@id'
        ])
        ->param([
            'goodsId' => Param::int()->desc('商品id')
        ]),

    Restful::delete('客户端用户取消点赞商品', ':goodsId')
        ->route('UserLike@cancel')
        ->token(1)
        ->param([
            'goodsId' => Param::int()->desc('商品id')
        ]),

    Restful::get('获取商品点赞数', ':goodsId')
        ->route('UserLike@getSumOfGoods')
        ->returnDesc([
            'like' => ['int', '商品点赞数']
        ])
        ->param([
            'goodsId' => Param::int()->desc('商品id')
        ]),
];

Restful::group('user/like', $routes);