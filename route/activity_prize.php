<?php

use api\route\{Restful, Param};

$routes = [
    Restful::get('获取活动的奖品', ':activityId/prizes')
        ->route('ActivityPrize@getPrizes')
        ->rulePattern([
            'activityId' => '@id'
        ])
        ->paginate(true)
        ->param([
            'activityId' => Param::int()->desc('活动id')->require(true)
        ])
        ->returnDesc([
            'image_info' => ['array', '奖品图片信息'],
            'name'      => ['string', '奖品名称'],
            'detail'    => ['string', '奖品描述'],
            'lottery_time' => ['string', '开奖时间'],
            'count'     => ['int', '奖品数量'],
        ]),

    Restful::post('添加活动奖品', ':activityId/prizes')
        ->route('ActivityPrize@add')
        ->rulePattern([
            'activityId' => '@id'
        ])
        ->param([
            'activityId' => Param::int()->desc('活动id')->require(true)
        ])
        ->post([
            'image' => Param::file()->desc('奖品封面图')->require(true),
            'name' => Param::string()->desc('奖品名称')->require(true),
            'detail' => Param::string(200)
                ->desc('奖品描述')
                ->setDefault(''),
            'lottery_time' => Param::int()->desc('开奖时间戳')->require(true),
            'count' => Param::int(6)->setDefault(1),
            'listorder' => Param::int()->desc('优先级')->setDefault(0),
        ]),

    Restful::put('修改活动奖品信息', ':activityId/prizes/:id')
        ->route('ActivityPrize@put')
        ->rulePattern([
            'activityId' => '@id',
            'id' => '@id'
        ])
        ->param([
            'activityId' => Param::int()->desc('活动id')->require(true),
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

Restful::group('activity', $routes);