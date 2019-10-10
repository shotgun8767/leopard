<?php /*a:6:{s:52:"F:\wamp\www\leopard\app\view\api_document\index.html";i:1568607068;s:45:"F:\wamp\www\leopard\app\view\common\head.html";i:1565199156;s:53:"F:\wamp\www\leopard\app\view\api_document\navbar.html";i:1563021373;s:54:"F:\wamp\www\leopard\app\view\api_document\sidebar.html";i:1563036614;s:54:"F:\wamp\www\leopard\app\view\api_document\content.html";i:1565195967;s:45:"F:\wamp\www\leopard\app\view\common\foot.html";i:1565594427;}*/ ?>



<!-- foot -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlentities($title); ?></title>
    <?php foreach($css as $item): ?>
        <link rel="stylesheet" type="text/css" href="__STATIC__css/<?php echo htmlentities($item); ?>.css">
    <?php endforeach; ?>
</head>
<body>




<!-- navbar -->

<nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand"><?php echo htmlentities($nav_title); ?></a>
</nav>

<div id="main">
    <?php if(($routes !== null)): ?>
        
<!-- sidebar -->

<div id="sidebar">
    <div id="sidebarSearch">
        <input type="text" class="form-control" id="searchInput" placeholder="请输入搜索关键词..." />
        <i class="fa fa-search"></i>
    </div>

    <div id="sidebarList">
        <ul>
            <?php foreach($routes as $i => $routeName): if(($i == $route_index)): ?>
                    <li class="list-selected"><?php echo htmlentities($routeName); ?></li>
                <?php else: ?>
                    <li><?php echo htmlentities($routeName); ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
    <?php endif; if(($route !== null)): ?>
        
<!-- content -->

<!-- 头部 -->
<div id="contentHead">
    <i class="fa fa-bars" id="sidebarControl"></i>
    <h3><?php echo htmlentities($route['name']); ?></h3>
</div>

<?php  $param_ex = !empty($route['param']) || !empty($route['post']);  $return_ex = !empty($route['return_data']); ?>


<div id="content">
    <!-- 选项卡标签 -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tab-base">基础信息</a>
        </li>
        <?php if(($param_ex)): ?>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-param">请求参数</a>
        </li>
        <?php endif; if(($return_ex)): ?>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-demo">示例</a>
        </li>
        <?php endif; ?>
    </ul>

    <!-- 选项卡内容 -->
    <div class="tab-content" id="content-tab-content">

        <!-- 选项卡：基础信息 -->
        <div id="tab-base" class="tab-pane active">
            <!-- METHOD -->
            <div class="assoc-box">
                <i class="assoc-key">method</i>
                <i class="assoc-value"><?php echo htmlentities($route['method']); ?></i>
            </div>

            <!-- url -->
            <div class="assoc-box" id="baseUrl">
                <i class="assoc-key">url</i>
                <i class="assoc-value" style="background-color: #ff9800;"><?php echo htmlentities($route['rule']); ?></i>
            </div>

            <!-- RESTFUL -->
            <div class="assoc-box">
                <i class="assoc-key">api</i>
                <i class="assoc-value" style="background-color: #5c6bc0;">RESTful</i>
            </div>

            <?php if(($route['token_require'])): ?>
                <blockquote class="quote-warning">
                    <span>调用本接口时需要在header中传递Token令牌</span>
                </blockquote>
            <?php endif; if(($route['desc'])): ?>
                <blockquote class="quote-success quote-content-default">
                    <span><?php echo htmlentities($route['desc']); ?></span>
                </blockquote>
            <?php endif; if(($route['return_desc'])): ?>
                <div class="gray-label">
                    <span></span><span></span>
                    <label>返回JSON数据包中的data</label>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">属性</th>
                        <th scope="col">类型</th>
                        <th scope="col">说明</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($route['return_desc'] as $name => $detail): ?>
                    <tr>
                        <td><?php echo htmlentities($name); ?></td>
                        <td><?php echo isset($detail[0]) ? htmlentities($detail[0]) : ''; ?></td>
                        <td><?php echo isset($detail[1]) ? htmlentities($detail[1]) : ''; ?></td>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <?php if(($param_ex)): ?>
        <!-- 选项卡：请求参数 -->
        <div id="tab-param" class="tab-pane">

            <?php if((!empty($route['param']))): ?>
            <div class="gray-label">
                <span></span><span></span>
                <label>param参数</label>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">名称</th>
                    <th scope="col">类型</th>
                    <th scope="col">是否必填</th>
                    <th scope="col">默认值</th>
                    <th scope="col">描述</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($route['param'] as $name => $detail): ?>
                <tr>
                    <td><?php echo htmlentities($name); ?></td>
                    <td><?php echo htmlentities($detail['type']); ?></td>
                    <td><?php echo !empty($detail['require']) ? '是' : '否';; ?></td>
                    <td><?php echo isset($detail['default']) ? htmlentities($detail['default']) : ''; ?></td>
                    <td><?php echo isset($detail['desc']) ? htmlentities($detail['desc']) : ''; ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; if((!empty($route['post']))): ?>
            <div class="gray-label">
                <span></span><span></span>
                <label>post参数</label>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">名称</th>
                    <th scope="col">类型</th>
                    <th scope="col">是否必填</th>
                    <th scope="col">默认值</th>
                    <th scope="col">描述</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($route['post'] as $name => $detail): ?>
                <tr>
                    <td><?php echo htmlentities($name); ?></td>
                    <td><?php echo htmlentities($detail['type']); ?></td>
                    <td><?php echo !empty($detail['require']) ? '是' : '否';; ?></td>
                    <td><?php echo isset($detail['default']) ? htmlentities($detail['default']) : ''; ?></td>
                    <td><?php echo isset($detail['desc']) ? htmlentities($detail['desc']) : ''; ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- 选项卡：示例 -->
        <div id="tab-demo" class="tab-pane">
            <?php if(($route['pattern'])): if(($pattern_param = ($route['pattern']['param']??[]))): ?>
                    <div class="gray-label">
                        <span></span><span></span>
                        <label>请求参数</label>
                    </div>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">属性</th>
                            <th scope="col">值</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($pattern_param as $name => $value): ?>
                            <tr>
                                <td><?php echo htmlentities($name); ?></td>
                                <td><?php echo htmlentities($value); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; if(($route['pattern']['token']??false)): ?>
                    <blockquote class="quote-warning">
                        <span>Token权限等级为：<?php echo isset($route['pattern']['token']['auth']) ? htmlentities($route['pattern']['token']['auth']) : 0; ?></span>
                    </blockquote>
                <?php endif; ?>
            <?php endif; if(($route['return_data']??0)): ?>
            <div class="gray-label">
                <span></span><span></span>
                <label>返回JSON包数据</label>
            </div>
            <pre class="hljs"><code><?php echo htmlentities($route['return_data']); ?></code></pre>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>
    <?php endif; ?>
</div>


<!-- foot -->

<?php if(($js)): foreach($js as $item): ?>
    <script type="text/javascript" src="__STATIC__/js/<?php echo htmlentities($item); ?>.js"></script>
    <?php endforeach; ?>
<?php endif; if(($static)): foreach($static as $item): ?>
    <script type="text/javascript" src="__STATIC__/<?php echo htmlentities($item); ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>


<script>hljs.initHighlightingOnLoad();    </script>

</body>
</html>