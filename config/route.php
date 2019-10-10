<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // pathinfo分隔符
    'pathinfo_depr'         => '/',
    // URL伪静态后缀
    'url_html_suffix'       => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'      => true,
    // 是否开启路由延迟解析
    'url_lazy_route'        => false,
    // 是否强制使用路由
    'url_route_must'        => true,
    // 合并路由规则
    'route_rule_merge'      => false,
    // 路由是否完全匹配
    'route_complete_match'  => true,
    // 使用注解路由
    'route_annotation'      => false,
    // 是否开启路由缓存
    'route_check_cache'     => false,
    // 路由缓存连接参数
    'route_cache_option'    => [],
    // 路由缓存Key
    'route_check_cache_key' => '',
    // 访问控制器层名称
    'controller_layer'      => 'controller',
    // 空控制器名
    'empty_controller'      => 'Error',
    // 是否使用控制器后缀
    'controller_suffix'     => false,
    // 默认的路由变量规则
    'default_route_pattern' => '[\w\.]+',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'           => true,
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'         => false,
    // 请求缓存有效期
    'request_cache_expire'  => null,
    // 全局请求缓存排除规则
    'request_cache_except'  => [],
    // 默认控制器名
    'default_controller'    => 'ApiHandle',
    // 默认操作名
    'default_action'        => 'handle',
    // 操作方法后缀
    'action_suffix'         => '',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler' => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'     => 'callback',
    // Api入口函数
    'api_index' => 'apiHandle/index',
    // 是否开启Restful多版本模式（忽略version）
    'restful_multi_version' => true,
    // Restful api的规则默认前缀
    'restful_rule_prefix'   => '',
    // Restful api的规则默认后缀
    'restful_rule_suffix'   => '',
    // Restful 是否在调用权限不可以调用的参数时抛出异常
    'restful_throw_over_auth' => false,
    // （分页模式）页数参数的键名
    'page_key'     => 'page',
    // （分页模式）每页行数参数的键名
    'row_key'      => 'row',
    // （分页模式）每页行数的默认值
    'row_default'   => 6
];
