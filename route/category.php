<?php

use api\route\{Restful, Route, Param};

$routes = [
    Restful::get('获取全部种类信息', 'all')
        ->route('Category@getAll')
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'id' => ['int', '种类id'],
            'name' => ['string', '种类名称'],
            'image_info' => ['array', '图片信息']
        ])
        ->dataMode(Route::DATA_MODE_MULTI)
        ->pattern([
            'param' => []
        ]),

    Restful::post('添加种类')
        ->route('Category@add')
        ->post([
            'image' => Param::file()->desc('图片')->require(true),
            'name' => Param::string()->desc('种类名称')->require(true),
            'listorder' => Param::string()->desc('优先级')->setDefault(0)
        ])
];

Restful::group('category', $routes);