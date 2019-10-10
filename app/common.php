<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use utility\general\Time;
use api\{ApiCore, Package};

/**
 * 计时开始
 *
 * @param string $name
 */
function timeOn(string $name = 'default')
{
    $GLOBALS['timer'][$name] = Time::now();
}

/**
 * 计时结束，返回计时时间
 *
 * @param string $name
 * @return float|int
 */
function timeOff(string $name = 'default')
{
    if (key_exists($name, $GLOBALS['timer'])) {
        $Timer = $GLOBALS['timer'][$name];
        if ($Timer instanceof Time) {
            return $Timer->difference();
        }
    }
    return 0;
}

/**
 * @param string $route 访问路由
 * @param array $param  参数
 * @return Package
 */
function api(string $route, array $param = []) : Package
{
    $ApiCore = (new ApiCore($route))->setParam($param);

    return $ApiCore->callRoute();
}

if (!function_exists('mb_str_split')) {
    function mb_str_split($str){
        return preg_split('/(?<!^)(?!$)/u', $str );
    }
}
