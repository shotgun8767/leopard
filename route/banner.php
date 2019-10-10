<?php

use api\route\{Restful, Param, Route};

$routes = [
    Restful::get('获取轮播图', '')
        ->route('Banner@getBanners')
        ->param([
            'limit' => Param::int(6)
                ->setDefault(6)
                ->desc('轮播图数量')
                ->validate([
                    'id' => "param 'limit' need to be positive"
                ])
        ])
        ->pattern([
            'param' => [
                'limit' => 4
            ]
        ])
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'id' => ['int', '轮播图id'],
            'image_info' => ['array', '轮播图图片信息']
        ]),

    Restful::post('添加轮播图')
        ->route('Banner@upload')
        ->param([
            'listorder' => Param::int(6)
                ->desc('优先级')
                ->setDefault(0)
        ])
];

Restful::group('banner', $routes);