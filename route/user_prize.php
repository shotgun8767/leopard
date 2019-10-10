<?php

use api\route\{Param, Restful};

$routes = [
    Restful::get('获取用户全部奖品')
        ->route('UserPrize@getUserPrizes')
        ->param([
            'status' => Param::int(1)->desc('奖品状态')
        ])
        ->token(1),

    Restful::post('奖品状态更新为已发送', ':id')
        ->route('UserPrize@PrizeSend')
        ->token(3)
        ->param([
            'id' => Param::int()->desc('实体奖品id')
        ])
        ->rulePattern([
            'id' => '@id'
        ]),

    Restful::post('奖品状态更新为已接收', ':id')
        ->route('UserPrize@PrizeReceive')
        ->token(1)
        ->param([
            'id' => Param::int()->desc('实体奖品id')
        ])
        ->rulePattern([
            'id' => '@id'
        ]),
];

Restful::group('user/prizes', $routes);