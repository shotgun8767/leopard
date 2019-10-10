<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

function r($url)
{
    return str_replace('\\', '/', $url);
}

// 域名
# server: defined('DOMAIN') or define('DOMAIN', 'https://' . r($_SERVER['SERVER_NAME']));
defined('DOMAIN') or define('DOMAIN', 'https://' . r($_SERVER['SERVER_NAME']) . '/leopard/public');
// HOST
defined('HOST') or define('HOST', $_SERVER['HTTP_HOST']);

// 应用目录
defined('APP_PATH') or define('APP_PATH', r(__DIR__ . '/../app/'));
// public 目录
defined('PUBLIC_PATH') or define('PUBLIC_PATH', r(__DIR__ . '/'));
// static 目录
defined('STATIC_PATH') or define('STATIC_PATH', PUBLIC_PATH . 'static/');
// resource 目录
defined('RESOURCE_PATH') or define('RESOURCE_PATH', PUBLIC_PATH . 'resource/');

// 域名下的public 目录
defined('DOMAIN_PUBLIC') or define('DOMAIN_PUBLIC', r(DOMAIN . '/'));
// 域名下的static 目录
defined('DOMAIN_STATIC') or define('DOMAIN_STATIC', DOMAIN_PUBLIC . 'static/');
// 域名下的resource目录
defined('DOMAIN_RESOURCE') or define('DOMAIN_RESOURCE', DOMAIN_PUBLIC . 'resource/');


require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
