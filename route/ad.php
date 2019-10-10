<?php

use api\route\{Restful, Param};

$routes = [
    Restful::get('获取所有广告', 'all')
        ->route('Advertisement@getAll')
        ->returnDesc([
            'id' => ['int', '广告id'],
            'title' => ['string', '广告标题'],
            'description' => ['string', '描述'],
            'image_info' => ['array', '图片信息']
        ])
];

Restful::group('ad', $routes);