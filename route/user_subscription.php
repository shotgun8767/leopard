<?php

use api\route\{Restful, Param};

$routes = [
    Restful::get('获取用户关注列表')
        ->route('UserSubscription@getUserSubscription')
        ->token(1)
        ->paginate(true),

    Restful::post('关注用户', ':foreignId')
        ->token(1)
        ->route('UserSubscription@add')
        ->param([
            'foreignId' => Param::int()->desc('被关注用户id')
        ]),

    Restful::delete('取消关注用户', ':foreignId')
        ->token(1)
        ->route('UserSubscription@cancel')
        ->param([
            'foreignId' => Param::int()->desc('被关注用户id')
        ]),

    Restful::get('获取用户粉丝列表', 'fans')
        ->token(1)
        ->route('UserSubscription@getUserFans')
        ->paginate(true)
];

Restful::group('user/subs', $routes);
