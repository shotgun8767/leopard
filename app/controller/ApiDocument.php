<?php

namespace app\controller;

use api\Route\Restful;
use api\route\RestfulRoute;
use think\facade\View;

class apiDocument
{
    public function index($index = 0, $search = "")
    {
        $routes = Restful::getRoutes();
        $routesName = [];
        foreach ($routes as $route) {
            if ($route instanceof RestfulRoute) {
                $name = $route->name();
                if (!$search || strstr($name, $search) !== false) {
                    $routesName[] = $route->name();
                }
            }
        }

        $Route = null;
        $routeName = $routesName[$index]??null;
        foreach ($routes as $route) {
            if ($route instanceof RestfulRoute) {
                if ($route->name() == $routeName) {
                    $Route = $route;
                    break;
                }
            }
        }

        if ($Route instanceof RestfulRoute) {
            $routeArray = $Route->toArray();
        } else {
            $routeArray = null;
        }

        $vars = [
            // CSS文件
            'css'   => [
                'api_document',
                'bootstrap.min',
                'font-awesome',
                'gray_scale',
                'quote',
                'highlight'
            ],
            // JS文件
            'js'    => [
                'jquery.min',
                'api_document',
                'bootstrap.bundle.min',
                'highlight.pack',
            ],
            'static' => [
                'layui/layui.js'
            ],
            // 网页标题
            'title' => 'Api documentation',
            // 导航栏标题
            'nav_title' => 'Leopard项目Api文档',
            // 全部路由
            'routes' => $routesName,
            // 选中的路由下标
            'route_index' => $index,
            // 选中路由标签
            'route'  => $routeArray,
            // static路径
            'static_path' => STATIC_PATH,
            // 当前域名
            'domain'    => DOMAIN . 'index.php?s='
        ];

        # server: $domain = "https://api.bar.rehellinen.cn";
        local : $domain = DOMAIN;

        return View::filter(function($content) use ($domain) {
            return str_replace("__STATIC__",$domain . '/static/',$content);
        })->assign($vars)->fetch();
        //__DIR__ . '/../../view/api_document/index.html'
    }
}