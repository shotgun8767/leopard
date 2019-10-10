<?php

use api\route\{Restful, Param};

$routes = [
    Restful::post('上传一张图片')
        ->route('Image@upload')
        ->returnDesc([
            'image_id' => ['int', '图片的id']
        ]),

    Restful::get('获取图片信息', ':id')
        ->route('Image@get')
        ->param([
            'id' => Param::int()->desc('图片id')->require(true),
            'detail' => Param::bool()->desc('是否获取原始图片id、图片宽度和高度')
        ])
        ->returnDesc([
            'image_url' => ['string', '图片地址'],
            'original' => ['int', '压缩前原始图片id'],
            'width' => ['int', '图片宽度'],
            'height' => ['int', '图片高度']
        ])
        ->rulePattern([
            'id' => '@id'
        ]),

    Restful::get('【对象储存】上传图片', 'obj')
        ->route('Image@uploadObj')
];

Restful::group('image', $routes);