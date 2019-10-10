<?php

return [
    'score' => [
        'sign_in'   => 5,
        'invitation'    => [
            'inviter' => 10,
            'invitee' => 20
        ],
        'user_run'  => [
            'step' => 1000,
            'max_score' => 20
        ],
        'upload_goods' => 20
    ],

    // 学校限制
    'school_restrict' => [
        'goods' => true,
        'user' => true
    ]
];