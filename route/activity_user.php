<?php

use api\route\{Param, Restful};

$routes = [
    Restful::post('用户报名参加活动', ':activityId/user')
        ->route('ActivityUser@participate')
        ->token(1)
        ->rulePattern([
            'activityId' => '@id'
        ])
        ->param([
            'activityId' => Param::int()->desc('活动id')->require(true)
        ]),

    Restful::delete('用户取消报名', ':activityId/user')
        ->route('ActivityUser@cancel')
        ->token(1)
        ->rulePattern([
            'activityId' => '@id'
        ])
        ->param([
            'activityId' => Param::int()->desc('活动id')->require(true)
        ]),

    Restful::get('获取活动的参与用户', ':activityId/users/all')
        ->route('ActivityUser@getUserOfActivity')
        ->paginate(true)
        ->rulePattern([
            'activityId' => '@id'
        ])
        ->param([
            'activityId' => Param::int()->desc('活动id')->require(true)
        ])
        ->returnDesc([
            'user_id' => ['int', '用户id'],
            'user_info' => ['array', '用户信息'],
            'user_info.name' => ['string', '用户名称'],
            'user_info.avatar_url' => ['string', '用户头像文件地址']
        ]),

    Restful::get('获取用户的全部活动信息', ':activityId/users/all')
        ->route('ActivityUser@getUserActivity')
        ->token(1)
        ->paginate(true)
        ->rulePattern([
            'activityId' => '@id'
        ])
        ->param([
            'activityId' => Param::int()->desc('活动id')->require(true)
        ])
        ->returnDesc([
            'activity_id' => ['int', '活动id'],
            'activity_info' => ['array', '活动信息']
        ]),
];

Restful::group('activity');