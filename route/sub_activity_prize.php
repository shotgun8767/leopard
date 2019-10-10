<?php

use api\route\{Restful, Param};

# 获取子活动的奖品
# 添加子活动奖品
# 修改子活动的奖品信息

$routes = [
    Restful::get('获取子活动的奖品', ':subActivityId/prizes')
        ->route('SubActivityPrize@getPrizes')
        ->rulePattern([
            'subActivityId' => '@id'
        ])
        ->paginate(true)
        ->param([
            'subActivityId' => Param::int()->desc('活动id')->require(true)
        ])
        ->returnDesc([
            'image_info' => ['array', '奖品图片信息'],
            'name'      => ['string', '奖品名称'],
            'detail'    => ['string', '奖品描述'],
            'lottery_time' => ['string', '开奖时间'],
            'count'     => ['int', '奖品数量'],
        ]),

    Restful::post('添加子活动奖品', ':subActivityId/prizes')
        ->route('SubActivityPrize@add')
        ->token(3)
        ->rulePattern([
            'subActivityId' => '@id'
        ])
        ->param([
            'subActivityId' => Param::int()->desc('活动id')->require(true)
        ])
        ->post([
            'image' => Param::file()->desc('奖品封面图')->require(true),
            'name' => Param::string()->desc('奖品名称')->require(true),
            'detail' => Param::string(200)
                ->desc('奖品描述')
                ->setDefault(''),
            'lottery_time' => Param::int()->desc('开奖时间戳')->require(true),
            'count' => Param::int(2)->desc('奖品数量，最大值为99')->setDefault(1),
            'listorder' => Param::int()->desc('优先级')->setDefault(0),
        ]),

    Restful::put('修改子活动奖品信息', ':subActivityId/prizes/:id')
        ->route('SubActivityPrize@put')
        ->rulePattern([
            'subActivityId' => '@id',
            'id' => '@id'
        ])
        ->param([
            'subActivityId' => Param::int()->desc('活动id')->require(true),
            'id' => Param::int()->desc('奖品id')->require(true)
        ])
        ->post([
            'image' => Param::file()->desc('奖品封面图')->require(true),
            'name' => Param::string()->desc('奖品名称')->require(true),
            'detail' => Param::string(200)
                ->desc('奖品描述')
                ->setDefault(''),
            'lottery_time' => Param::int()->desc('开奖时间戳')->require(true),
            'count' => Param::int(6)->setDefault(1),
            'listorder' => Param::int()->desc('优先级')->setDefault(0)
        ])
];

Restful::group('sub_activity', $routes);