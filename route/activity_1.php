<?php

use api\Route\{Restful, Param};

$routes = [
    Restful::get('【抽奖活动】开始抽奖', 'sub_activity/:subActivityId/draw')
        ->route('Activity1@draw')
        ->rulePattern([
            'subActivityId' => '@id',
        ])
        ->param([
            'subActivityId' => Param::int()->desc('子活动id')->require(true),
        ])
        ->desc('人工开启抽奖系统，摇奖。该操作必须在子活动结束后才会生效')
        ->token(3),
];

Restful::group('activity/1', $routes);
