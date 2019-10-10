<?php

use api\route\{Restful, Param, Route};

$routes = [
    Restful::get('获取用户发布的商品', 'user')
        ->route('Goods@getUserGoods')
        ->token(1)
        ->dataMode(Route::DATA_MODE_MULTI)
        ->paginate(true)
        ->param([
            'status' => Param::int()->require(true)->desc('商品状态。1表示商品在货架上，2表示已售出，3表示已下架。'),
        ])
        ->returnDesc([
            'id' => ['int', '商品id'],
            'name' => ['string', '名称'],
            'price' => ['int', '价格'],
            'quantity' => ['int', '库存'],
            'description' => ['int', '详细描述'],
            'image_info' => ['array', '图片信息'],
            'category_info' => ['array', '种类信息']
        ]),

    Restful::get('获取随机商品信息', 'random')
        ->route('Goods@getRandom')
        ->token(1)
        ->param([
            'limit' => Param::int(2)->desc('获取数量')->require(true),
            'subs' => Param::bool()->desc('是否只获取已关注商家的商品')->setDefault(false),
            'log' => Param::bool()->desc('是否记录本次商品id')->setDefault(false),
            'no_repeat' => Param::bool()->desc('是否无重复获取商品信息')->setDefault(false),
            'category_id' => Param::int()->desc('限定的种类的id，0表示无限制')->setDefault(0),
            'ex_category_id' => Param::int()->desc('排除的种类id，0不排除任何种类')->setDefault(0)
        ])
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'id' => ['int', '商品id'],
            'name' => ['string', '商品名称'],
            'subtitle' => ['string', '商品副标题'],
            'price' => ['int', '商品价格'],
            'image_info' => ['array', '商品图像信息'],
            'category_info' => ['array', '所属种类信息'],
            'seller_info' => ['array', '商家信息'],
            'like' => ['bool', '商品点赞数'],
            'is_like' => ['bool', '用户是否点赞']
        ]),

    Restful::get('获取随机商品信息【新】', 'rand')
        ->route('Goods@getRand')
        ->token(1)
        ->param([
            'limit' => Param::int(2)->desc('获取数量')->require(true),
            'page' => Param::int()->desc('页码')->require(true)
        ])
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'id' => ['int', '商品id'],
            'name' => ['string', '商品名称'],
            'subtitle' => ['string', '商品副标题'],
            'price' => ['int', '商品价格'],
            'image_info' => ['array', '商品图像信息'],
            'category_info' => ['array', '所属种类信息'],
            'seller_info' => ['array', '商家信息'],
            'like' => ['bool', '商品点赞数'],
            'is_like' => ['bool', '用户是否点赞']
        ]),

    Restful::get('获取随机商品信息【图书音像】', 'rand/books')
        ->route('Goods@getRandBooks')
        ->token(1)
        ->param([
            'limit' => Param::int(2)->desc('获取数量')->require(true),
            'page' => Param::int()->desc('页码')->require(true)
        ])
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'id' => ['int', '商品id'],
            'name' => ['string', '商品名称'],
            'subtitle' => ['string', '商品副标题'],
            'price' => ['int', '商品价格'],
            'image_info' => ['array', '商品图像信息'],
            'category_info' => ['array', '所属种类信息'],
            'seller_info' => ['array', '商家信息'],
            'like' => ['bool', '商品点赞数'],
            'is_like' => ['bool', '用户是否点赞']
        ]),

    Restful::get('获取随机商品信息【其他】', 'rand/else')
        ->route('Goods@getRandElse')
        ->token(1)
        ->param([
            'limit' => Param::int(2)->desc('获取数量')->require(true),
            'page' => Param::int()->desc('页码')->require(true)
        ])
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'id' => ['int', '商品id'],
            'name' => ['string', '商品名称'],
            'subtitle' => ['string', '商品副标题'],
            'price' => ['int', '商品价格'],
            'image_info' => ['array', '商品图像信息'],
            'category_info' => ['array', '所属种类信息'],
            'seller_info' => ['array', '商家信息'],
            'like' => ['bool', '商品点赞数'],
            'is_like' => ['bool', '用户是否点赞']
        ]),

    Restful::get('获取关注商品', 'subs')
        ->route('Goods@getSubs')
        ->token(1)
        ->desc('根据关注用户商品的发布顺序倒叙返回商品信息')
        ->paginate(true)
        ->dataMode(Route::DATA_MODE_MULTI)
        ->returnDesc([
            'id' => ['int', '商品id'],
            'name' => ['string', '商品名称'],
            'subtitle' => ['string', '商品副标题'],
            'price' => ['int', '商品价格'],
            'image_info' => ['array', '商品图像信息'],
            'category_info' => ['array', '所属种类信息'],
            'seller_info' => ['array', '商家信息'],
            'like' => ['bool', '商品点赞数'],
            'is_like' => ['bool', '用户是否点赞']
        ]),

    Restful::get('获取商品信息', ':id')
        ->route('Goods@get')
        ->token(1)
        ->rulePattern([
            'id' => '@id'
        ])
        ->param([
            'id' => Param::int()->desc('商品id')
        ])
        ->returnDesc([
            'name' => ['string', '商品名称'],
            'subtitle' => ['string', '副标题'],
            'price' => ['int', '价格'],
            'quantity' => ['int', '库存量'],
            'description' => ['string', '详细描述'],
            'image_info' => ['array', '图像信息'],
            'category_info' => ['array', '种类信息'],
            'seller_info' => ['array', '卖家信息'],
            'like' => ['int', '点赞人数'],
            'is_like' => ['bool', '客户端用户是否点赞'],
            'is_subs_seller' => ['bool', '客户端用户是否关注卖家']
        ])
        ->pattern([
            'param' => [
                'id' => 585
            ]
        ]),

    Restful::post('发布商品')
        ->route('Goods@upload')
        ->token(1)
        ->post([
            'category_id' => Param::int()->desc('所属种类的id')->require(true),
            'image' => Param::file()->desc('商品图片'),
            'name' => Param::string(50)->desc('商品名称')->require(true),
            'subtitle' => Param::string(40)->setDefault(),
            'price' => Param::int(11)->desc('商品价格')->require(true),
            'quantity' => Param::int(5)->desc('数量/库存')->setDefault(1),
            'description' => Param::string('255')->desc('详细描述')->setDefault(),
        ])
        ->returnDesc([
            'id' => ['int', '商品id']
        ]),

    Restful::put('修改商品', ':id')
        ->route('Goods@edit')
        ->token(1)
        ->param([
            'id' => Param::int()->desc('商品id')
        ])
        ->post([
            'image'         => Param::file()->desc('商品图片'),
            'name'          => Param::string(50)->desc('商品名称'),
            'subtitle'      => Param::string(40)->desc('商品副标题'),
            'price'         => Param::int(11)->desc('商品价格'),
            'quantity'      => Param::int(5)->desc('数量/库存'),
            'description'   => Param::string('255')->desc('详细描述'),
        ]),

    Restful::post('修改商品封面', ':id/cover')
        ->route('Goods@editCover')
        ->token(1)
        ->desc('仅供微信小程序使用')
        ->param([
            'id' => Param::int()->desc('商品id')
        ])
        ->post([
            'image' => Param::file()->desc('商品图片'),
        ]),

    Restful::delete('删除商品', ':id')
        ->route('Goods@delete')
        ->token(1)
        ->param([
            'id' => Param::int()->desc('商品id')
        ]),

    Restful::get('搜索商品', 'search')
        ->route('Goods@search')
        ->desc('使用最原始的模糊搜寻，查询商品名或商品描述中含有查询字符串的商品')
        ->token(1)
        ->param([
            'query' => Param::string()->desc('查询字符串')->require(true)
        ])
        ->paginate(true)

];

Restful::group('goods', $routes);