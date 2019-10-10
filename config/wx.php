<?php

return [
    // 应用开发id
    'app_id' => 'wxdb592936d6083ccb',
    // 应用开发密码
    'app_secret' => '02c00a9a7afe360baf4a4103288e24dc',
    // 商户id
    'mch_id'    => '1531349751',
    // 商户平台秘钥key
    'mch_key'       => '2fef801bca148d644e6f81ff89b93api',
    // 微信api接口
    'api' => [
        // 获取openid
        'getOpenid' => 'https://api.weixin.qq.com/sns/jscode2session',
        // 统一下单
        'unified_order' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
        // 获取ACCESS TOKEN
        'access_token' => 'https://api.weixin.qq.com/cgi-bin/token',
        // 生成小程序二维码
        'mini_program_qrcode' => 'https://api.weixin.qq.com/wxa/getwxacodeunlimit',
    ],
    //
    'pay_notify_url' => '',

    // 微信端token过期时间（秒）
    'token_expire' => 15552000
];

