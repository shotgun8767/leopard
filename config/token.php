<?php

const TOKEN_VERSION = 'v1.2';

return [
    // ����
    'config' => [
        'secret' => 'leopard' . TOKEN_VERSION
    ],
    // Token����ʱ��180��
    'exp_time' => 15552000
];