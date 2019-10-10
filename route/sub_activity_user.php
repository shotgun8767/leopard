<?php

use api\route\{Param, Restful};

# 用户报名子活动
# 用户取消报名子活动

$routes = [
    Restful::post('用户报名子活动', ':subActivityId/user')
        ->route('SubActivity@participate')
        ->token(1)
        ->param([
            'subActivityId' => Param::int()->desc('子活动id')->require(true),
        ])
        ->rulePattern([
            'subActivityId' => '@id'
        ]),

    Restful::delete('用户取消报名子活动', ':subActivityId/user')
        ->route('SubActivity@cancel')
        ->token(1)
        ->param([
            'subActivityId' => Param::int()->desc('子活动id')->require(true),
        ])
        ->rulePattern([
            'subActivityId' => '@id'
        ]),
];

Restful::group('sub_activity');