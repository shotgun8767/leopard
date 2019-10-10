<?php

use api\route\{Restful, Param};

$routes = [
    Restful::get('获取用户信息', ':userId')
        ->route('User@getInfoById')
        ->token(1)
        ->desc('通过用户的id获取用户的部分信息。')
        ->param([
            'userId' => Param::int()->desc('用户id')
        ])
        ->rulePattern([
            'userId' => '@id'
        ])
        ->pattern([
            'param' => [
                'userId' => 1,
            ],
            'token' => [
                'payload' => ['id' => 2],
                'auth' => 1
            ]
        ])
        ->returnDesc([
            'id'        => ['int', '用户id'],
            'score'     => ['int', '积分/Bar豆'],
            'name'      => ['string', '姓名'],
            'nick_name' => ['string', '昵称'],
            'number'    => ['string', '学号'],
            'school_info' => ['array', '学校信息'],
            'address'   => ['string', '详细地址'],
            'major'     => ['string', '转业'],
            'telephone' => ['int', '手机号码'],
            'year'      => ['int', '入学年份'],
            'avatar_url'=> ['string', '头像文件url'],
            'subs_num'  => ['int', '用户粉丝'],
            'subs'      => ['bool', '客户端用户是否关注该用户']
        ]),

    Restful::get('获取客户端用户信息')
        ->route('User@getInfo')
        ->token(1)
        ->returnDesc([
            'score'     => ['int', '积分/Bar豆'],
            'name'      => ['string', '姓名'],
            'nick_name' => ['string', '昵称'],
            'number'    => ['string', '学号'],
            'school_info' => ['array', '学校信息'],
            'address'   => ['string', '详细地址'],
            'major'     => ['string', '转业'],
            'telephone' => ['int', '手机号码'],
            'year'      => ['int', '入学年份'],
            'avatar_url'=> ['string', '头像文件url'],
            'subs_num'  => ['int', '用户粉丝'],
        ]),

    Restful::put('编辑客户端用户信息')
        ->route('User@edit')
        ->token(1)
        ->desc('客户端用户编辑个人信息。')
        ->post([
            'name'      => Param::string(20)->desc('姓名'),
            'nick_name' => Param::string(20)->desc('昵称'),
            'number'    => Param::string(20)->desc('学号'),
            'school_id' => Param::int()->desc('学校id'),
            'address'   => Param::string(50)->desc('详细地址'),
            'major'     => Param::string(50)->desc('专业'),
            'telephone' => Param::bigint(11)->desc('手机号码'),
            'year'      => Param::int(4)->desc('入学年份'),
            'avatar_url'=> Param::string(50)->desc('头像url'),
            'invitation_code' => Param::string(16)->desc('用户邀请码'),
        ])
        ->returnDesc([
            'inv_success' => ['bool', '是否成功执行邀请码'],
            'inviter_id' => ['邀请码对应邀请者的id'],
            'inviter_score_plus' => ['邀请者积分提升']
        ]),

    Restful::get('获取用户签到状态', 'sign')
        ->route('User@getSignStatus')
        ->token(1)
        ->returnDesc([
            'status' => ['array', '用户签到状态']
        ]),

    Restful::put('用户签到', 'sign')
        ->route('User@sign')
        ->token(1)
        ->desc('每日只能签到一次。')
        ->returnDesc([
            'score_plus'=> ['int', '本次签到增加的积分'],
            'status'    => ['array', '本周签到情况，1表示已签到'],
            'sign_times'=> ['int', '本周签到次数']
        ]),

    Restful::get('获取用户邀请码', 'invitation/code')
        ->route('User@getInvitationCode')
        ->token(1)
        ->desc('客户端用户获取本人邀请码')
        ->returnDesc([
            'code' => ['int', '16位邀请码']
        ]),

    Restful::post('根据邀请码增加邀请者积分', 'invitation/score')
        ->route('User@invitationGetScore')
        ->token(1)
        ->desc('该接口应为被邀请者客户端调用，结果是双方用户增加积分。')
        ->returnDesc([
            'invitee_score_plus' => ['int', '被邀请者增加的积分值'],
            'inviter_id' => ['int', '邀请者id'],
            'inviter_score_plus' => ['int', '邀请者增加的积分值']
        ])
        ->param([
            'code' => Param::bigint(16)
                ->desc('邀请码')
                ->require(true)
        ])
];

Restful::group('user', $routes);