<?php

use think\facade\Route;
use think\App;
use api\route\Restful;

/**
 * 没有匹配的路由时输出
 */
Route::miss(function () {
    api("ApiHandle@missRule");
});

Route::get('', function () {
    echo 'leopard api';
});

/**
 * 输出当前框架版本信息
 */
Route::get('version', function () {
    echo "<h2>PHP version: " . phpversion() . "</h2>";
    echo "<h2>ThinkPHP version: " . APP::VERSION . "<h2>";
    echo "<h2>ThinkPHP6 LDK version: 1.1.4" . "</h2>";
    echo phpinfo();
    return null;
});

/**
 * 输出当前Restful Api版本号
 */
Route::get('restful/versions', function () {
   echo "<h2>" . Restful::availableVersions() . "</h2>";
   return null;
});

/**
 * 输出已注册的Restful api路由
 */
Route::get('restful/routes', function () {
    return null;
});

/**
 * Api文档
 */
Route::get('document', 'api_document/index');
