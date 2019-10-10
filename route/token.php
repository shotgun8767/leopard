<?php

use api\route\{Param, Restful};

$routes = [
    Restful::get('获取测试用Token', 'test')
        ->route('Token@getTest')
        ->desc('负载中不包含session_key。除开发人员外禁止使用。')
        ->param([
            'user_id' => Param::int()
                ->desc('用户id')
                ->require(true),
            'dev_pwd' => Param::string()
                ->desc('开发者密码')
                ->require(true)
        ])
        ->returnDesc([
            'token' => ['string', 'Token字符串'],
            'expire_time' => ['int', 'Token到期时间戳'],
            'expire_date' => ['string', 'Token到期时间']
        ]),

    Restful::get('获取Token信息')
        ->route('Token@getInfo')
        ->desc('仅该接口的token必须在param中传递！')
        ->param([
            'token' => Param::string()
                ->desc('用户token')
                ->require(true)
        ])
        ->pattern([
            'param' => [
                'token' => 'eyJhbGciOiJtZDUiLCJ0eXAiOiJKV1QifQ==.eyJpZCI6IjEiLCJzayI6IlwveE9SZ2hDNjdiMjdPNGZWY3JcL2lTQT09IiwiZXhwIjowLCJhdXRoIjoiOGNhNjI5OGIifQ==.301734c33def053e6d2666dc70d9c166'
            ]
        ])
        ->returnDesc([
            'is_expire' => ['bool', 'Token是否过期'],
            'expire_time' => ['int', '过期时间'],
            'payload' => ['array', 'Token中的负载']
        ]),

    Restful::post('修改Token权限', 'auth')
        ->route('Token@TokenAuthUpdate')
        ->desc('开发者密码问后台开发人员索取')
        ->param([
            'token' => Param::string()
                ->desc('用户token')
                ->require(true),
            'dev_pwd' => Param::string()
                ->desc('开发者密码')
                ->require(true),
            'auth' => Param::string()
                ->desc('权限等级')
                ->require(true)
        ])
];

Restful::group('token', $routes);