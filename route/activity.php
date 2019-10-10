<?php

use api\route\{Param, Restful, Route};

# 获取活动内容
# 新建活动
# 获取某活动的所有子活动
# 新增子活动

$routes = [
    Restful::get('获取活动内容', ':activityId')
        ->route('Activity@getInfo')
        ->rulePattern([
            'activityId' => '@id'
        ])
        ->param([
            'detail' => Param::bool()->desc('是否获取详细内容。')->setDefault(true),
            'activityId' => Param::int()->desc('活动id')->require(true)
        ])
        ->returnDesc([
            'name' => ['string', '活动名称'],
            'detail' => ['string', '详细描述'],
            'image_info' => ['array', '活动封面信息'],
        ]),

    Restful::post('新建活动')
        ->route('Activity@upload')
        ->token(2)
        ->post([
            'name' => Param::string()->desc('活动名')->require(true),
            'detail' => Param::string()->desc('活动描述')->setDefault(''),
            'listorder' => Param::int(6)->desc('优先级')->setDefault(0),
            'start_time' => Param::int()->desc('活动起始时间戳')->setDefault(0),
            'end_time' => Param::int()->desc('活动结束时间戳')->setDefault(0),
            'image' => Param::file()->desc('活动封面图片文件')->require(true)
        ]),

    Restful::get('获取所有活动', 'all')
        ->route('Activity@getAll')
        ->returnDesc([
            'id' => ['int', '子活动id']
        ]),

    Restful::get('获取活动的所有子活动', ':activityId/sub_activity')
        ->route('SubActivity@getAll')
        ->token(1)
        ->rulePattern([
            'activityId' => '@id'
        ])
        ->param([
            'activityId' => Param::int()->desc('隶属活动id')->require(true)
        ])
        ->returnDesc([
            'id' => ['int', '子活动id']
        ]),

    Restful::post('新增子活动', ':activityId/sub_activity')
        ->route('SubActivity@add')
        ->token(2)
        ->rulePattern([
            'activityId' => '@id'
        ])
        ->param([
            'activityId' => Param::int()->desc('隶属活动id')->require(true)
        ])
        ->post([
            'listorder' => Param::smallint()
                ->desc('优先级')
                ->setDefault(0),
            'name' => param::string()
                ->desc('子活动标题')
                ->require(true),
            'enter_start_time' => Param::int()
                ->desc('报名开始时间。0表示没有要求。')
                ->setDefault(0),
            'enter_end_time' => Param::int()
                ->desc('报名结束时间。0表示没有要求。')
                ->setDefault(0),
            'start_time' => Param::int()
                ->desc('子活动开始时间')
                ->require(true),
            'end_time' => Param::int()
                ->desc('子活动结束时间')
                ->require(true),
            'capacity' => Param::int()
                ->desc('报名人数上限。0表示没有上限')
                ->setDefault(0),
            'detail' => Param::string(200)
                ->desc('子活动描述')
                ->setDefault('')
        ]),
];
Restful::group('activity', $routes);