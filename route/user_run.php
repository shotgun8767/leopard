<?php

use api\route\{Restful, Param};

$routes = [
    Restful::get('微信端获取当前步数')
        ->token(1)
        ->route('UserRun@getSteps')
        ->desc('微信步数按日结算，每天只能结算一次，获取一次积分。')
        ->param([
            'encrypted_data' => Param::string()
                ->require(true)
                ->desc('加密的用户数据'),
            'iv' => Param::string()
                ->require(true)
                ->desc('与用户数据一同返回的初始向量'),
        ])
        ->returnDesc([
            'step'      => ['int', '加密数据中的步数'],
            'timestamp' => ['int', '加密数据中的时间戳']
        ]),

    Restful::put('根据微信步数换取积分', 'score')
        ->route('UserRun@getScore')
        ->token(1)
        ->param([
            'encrypted_data' => Param::string()
                ->require(true)
                ->desc('加密的用户数据'),
            'iv' => Param::string()
                ->require(true)
                ->desc('与用户数据一同返回的初始向量'),
        ])
        ->returnDesc([
            'score_plus'=> ['int', '积分增加值'],
            'step'      => ['int', '加密数据中的步数'],
            'timestamp' => ['int', '加密数据中的时间戳']
        ]),

    Restful::get('获取微信步数总和', 'score/sum')
        ->route('UserRun@sum')
        ->token(1)
        ->desc('获取一定时间区域内的微信步数。微信步数是按天结算的，因此时间戳会先转化为日期。')
        ->param([
            'start' => Param::int()
                ->require(true)
                ->desc('起始时间戳'),
            'end'   => Param::int()
                ->require(true)
                ->desc('结束时间戳')
        ])
        ->returnDesc([
            'sum' => ['int', '步数总和']
        ]),

    Restful::get('今日是否已兑换步数', 'status')
        ->route('UserRun@getStatus')
        ->token(1)
        ->returnDesc([
            'is_converted' => ['bool', '是否已兑换步数']
        ])
];

Restful::group('user/run', $routes);