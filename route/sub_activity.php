<?php

use api\route\{Param, Restful, Route};

# 获取子活动的全部报名用户
# 获取用户报名的所有子活动

$routes = [
    Restful::get('获取子活动的全部报名用户', ':subActivityId/users')
        ->route('subActivity@getUsers')
        ->paginate(true)
        ->param([
            'subActivityId' => Param::int()->desc('子活动id')->require(true)
        ])
        ->rulePattern([
            'subActivityId' => '@id'
        ])
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'user_id' => ['int', '用户id'],
            'user_info' => ['array', '用户信息']
        ]),

    Restful::get('获取子活动的报名人数', ':subActivityId/users/count')
        ->route('subActivity@getUserCount')
        ->param([
            'subActivityId' => Param::int()->desc('子活动id')->require(true)
        ])
        ->rulePattern([
            'subActivityId' => '@id'
        ])
        ->returnDesc([
            'num' => ['int', '报名人数']
        ]),

    Restful::get('获取用户报名的所有子活动', 'user')
        ->route('subActivity@getUsersSubActivity')
        ->token(1)
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'sub_activity_id' => ['int', '子活动id'],
            'sub_activity_info' => ['array', '子活动信息'],
            'sub_activity_info.activity_info' => ['array', '子活动所属活动信息'],
            'sub_activity_info.image_info' => ['array', '子活动封面图片信息']
        ]),

    Restful::get('获取子活动开奖情况', ':subActivityId/result')
        ->route('subActivity@getResult')
        ->token(1)
        ->param([
            'subActivityId' => Param::int()->desc('子活动id')->require(true)
        ])
        ->rulePattern([
            'subActivityId' => '@id'
        ]),
];

Restful::group('sub_activity', $routes);