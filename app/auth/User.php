<?php

namespace app\auth;

class User
{
    // 游客
    const VISITOR   = 0;
    // 普通用户
    const COMMON    = 1;
    // 合作伙伴
    const PARTNER   = 2;
    // 后台管理人员
    const ADMIN     = 3;
    // 开发者
    const DEVELOPER = 4;
}