<?php

use api\route\{Restful, Param, Route};

$routes = [
    Restful::get('获取所有学校', 'all')
        ->route('School@getAll')
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'id' => ['int', '学校id'],
            'name' => ['string', '学校名称']
        ])
        ->pattern([
            'param' => []
        ]),

    Restful::post('上传学校')
        ->route('School@add')
        ->post([
            'name' => Param::string(20)->desc('学校名称')->require(true),
            'listorder' => Param::smallint()->desc('优先级')->setDefault(0)
        ])
];

Restful::group('school', $routes);