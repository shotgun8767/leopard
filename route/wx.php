<?php

use api\route\{Restful, Param};

$routes = [
    Restful::get('根据code获取Token', 'token/:code')
        ->route('Wx@getTokenByCode')
        ->desc('token的有效期为半年。token过期后请重新获取。同一个code只能获取一次token。')
        ->param([
            'code' => Param::string()->desc('微信给的code')->require(true)
        ])
        ->returnDesc([
            'token' => ['string', 'Token字符串'],
            'expire_time' => ['int', 'Token到期时间戳'],
            'expire_date' => ['string', 'Token到期时间']
        ]),

    Restful::get('获取微信小程序二维码', 'mini-program/qrcode')
        ->route('Wx@getQRCode')
        ->param([
            'scene' => Param::string(),
            'page'  => Param::int()
        ])
        ->returnDesc([
            'path' => ['string', '文件路径'],
            'create_time' => ['int', '文件添加时间戳'],
        ]),

    Restful::get('获取微信小程序用户协议', 'mini-program/agreement')
        ->route('Wx@getAgreement')
        ->returnDesc([
            'title' => ['string', '标题'],
            'text' => ['string', '用户协议内容']
        ])
        ->pattern([
            'param' => []
        ])
];

Restful::group('wx', $routes);