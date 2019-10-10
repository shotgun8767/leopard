<?php

const TOKEN_VERSION = 'v1.2';

return [
    // 配置
    'config' => [
        'secret' => 'leopard' . TOKEN_VERSION
    ],
    // Token过期时间180天
    'exp_time' => 15552000
];