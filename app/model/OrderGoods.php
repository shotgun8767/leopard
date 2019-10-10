<?php

namespace app\model;

use app\exception\order\OrderException;
use app\exception\order\OrderGoodsException;
use model\BaseModel;

class OrderGoods extends BaseModel
{
    protected $hidden = ['listorder', 'order_id', 'image_id'];

    protected $with = [
        'order' => [
            'OrderInfo' => [
                'field' => ['order_no', 'total_price', 'seller_id', 'buyer_id']
            ]
        ]
    ];

    /**
     * 获取用户全部商品订单
     * @param int $buyerId
     * @return array
     */
    public function getBuyerOrderGoods(int $buyerId)
    {
        $field = [
            'this.id', 'goods_id', 'image_id','this.name', 'this.price', 'this.total_price', 'this.count' => 'num',
            'this.buyer_action', 'this.seller_action', 'g.seller_id'
        ];

        $this
            ->alias('this')
            ->join(['order'=>'o'],'o.id = this.order_id')
            ->join(['goods' => 'g'], 'g.id = this.goods_id')
            ->join(['user' => 'u'], 'u.id = g.seller_id');

        $res = $this
            ->multi()
            ->advancedWith(['GoodsInfo', 'ImageInfo' => function ($query) {
                $query->field(['id', 'image_url']);
            }])
            ->getArray(['o.buyer_id' => $buyerId], $field);

        $User = new User;
        foreach ($res as $key => $item) {
            $res[$key]['seller_info'] = $User->removeOption()->getArray($item['seller_id'], ['id', 'nick_name', 'avatar_url']);
        }

        return $res;
    }

    /**
     * 获取卖家全部订单
     * @param int $sellerId
     * @return array|bool
     */
    public function getSellerOrderGoods(int $sellerId)
    {
        $field = [
            'this.id', 'goods_id', 'image_id','this.name', 'this.price', 'this.total_price', 'this.count' => 'num',
            'this.buyer_action', 'this.seller_action', 'o.buyer_id', 'g.status' => 'goods_status'
        ];

        $this
            ->alias('this')
            ->join(['order'=>'o'],'o.id = this.order_id')
            ->join(['goods' => 'g'], 'g.id = this.goods_id')
            ->join(['user' => 'u'], 'u.id = g.seller_id');

        $res = $this
            ->multi()
            ->advancedWith(['GoodsInfo', 'ImageInfo' => function ($query) {
                $query->field(['id', 'image_url']);
            }])
            ->getArray(['o.seller_id' => $sellerId], $field);

        $User = new User;
        foreach ($res as $key => $item) {
            $res[$key]['buyer_info'] = $User->removeOption()->getArray($item['buyer_id'], ['id', 'nick_name', 'avatar_url']);
        }

        return $res;
    }

    /**
     * 新增订单
     * @param int $userId
     * @param int $goodsId
     * @param int $count
     * @param string $remark
     * @param string $address
     * @return array|bool
     */
    public function new(
        int $userId,
        int $goodsId,
        int $count,
        string $remark,
        string $address)
    {
        $Goods = new Goods;
        $goodsInfo = $Goods->getInfo($goodsId);
        if (!$goodsInfo) {
            // 商品不存在
            return false;
        }

        $name       = $goodsInfo['name'];
        $price      = $goodsInfo['price'];
        $quantity   = $goodsInfo['quantity'];
        $sellerId   = $goodsInfo['seller_info']['id'];

//        // 检测到用户购买自己发布的商品
//        if ($sellerId == $userId) {
//            throw new OrderGoodsException(161001);
//        }

        // 生成订单
        $orderId = (new Order)->new($count, $price * $count, $userId, $sellerId);
        if ($orderId === -1) {
            throw new OrderGoodsException(161002);
        }
        elseif ($orderId === -2) {
            throw new OrderGoodsException(161003);
        }

        // 商品数量减少
        $stock = $quantity > 0 ? 1 : 0;


        if ($stock) {
            $Goods->dec('quantity', 1);
            $Goods->getQuery()->update();
            if ($quantity == 1) {
                $Goods
                    ->removeOption()
                    ->updateStatus($goodsId, 2);
            }
        } else {
            throw new OrderGoodsException(161020);
        }


        $orderId = $orderId['id'];
        // 生成商品订单
        $data = [
            'goods_id' => $goodsId,
            'price' => $price,
            'count' => $count,
            'total_price' => $price * $count,
            'name' => $name,
            'remark' => $remark,
            'stock' => 1,
            'order_id' => $orderId,
            'update_time' => time(),
            'address' => $address,
            'seller_action' => 1,
            'create_time' => time()
        ];
        $res = $this->inserts($data);

        $data['id'] = $res;
        return $data;
    }

    /**
     * 卖家操作
     * @param int $sellerId 商家id
     * @param int $id       商品订单id
     * @param int $action   操作
     * @return mixed
     */
    public function updateSellerAction(int $sellerId, int $id, int $action)
    {
        $orderGoodsInfo = $this
            ->advancedWith($this->with['order'])
            ->getArray($id, ['goods_id', 'order_id', 'count', 'seller_action']);

        if ($orderGoodsInfo) {
            if ($orderGoodsInfo['seller_action'] == $action) {
                throw new OrderGoodsException(161004);
            }
            if ($sellerId != $orderGoodsInfo['order_info']['seller_id']) {
                throw new OrderGoodsException(161005);
            }
        } else {
            throw new OrderGoodsException(161006);
        }

        $count = $orderGoodsInfo['count'];
        $goodsId = $orderGoodsInfo['goods_id'];
        $originAction = $orderGoodsInfo['seller_action'];

        if ($action == 1) {
            # 接单
            if ($originAction != 0 && $originAction != 3) {
                throw new OrderGoodsException(161009);
            }
            // 接单操作，减少该商品库存
            $Goods = new Goods;
            $quantity = $Goods->getField('quantity', $goodsId);
            if (is_null($quantity)) {
                // 未找到商品信息
                throw new OrderGoodsException(161007);
            } else {
                if ($quantity >= $count) {
                    // 库存量大于等于数量，扣除库存
                    $Goods->whereBase($goodsId)->dec('quantity', $count);
                } else {
                    throw new OrderGoodsException(161008);
                }
            }
        }
        elseif ($action == 2) {
            # 拒绝
            if ($originAction == 4) {
                throw new OrderGoodsException(161010);
            }

            // 销毁订单
            $orderNo = $orderGoodsInfo['order_info']['order_no'];
            (new Order)->destruct($orderNo);
        }
        elseif ($originAction == 3) {
            # 库存不足，尚未接单
            if ($originAction == 4 or $originAction == 1) {
                throw new OrderGoodsException(161011);
            }
        }
        elseif ($originAction == 4) {
            # 完成送货
            if ($originAction != 1) {
                throw new OrderGoodsException(161012);
            }
            // 完成送货，更新完成送货时间
            $update['finish_time'] = time();
        }

        // 更新卖家操作
        $update['seller_action'] = $action;
        $update['update_time'] = time();
        return $this
            ->removeOption()
            ->updates($id, $update);
    }

    /**
     * 买家操作
     * @param int $buyerId
     * @param int $id
     * @param int $action
     * @throws OrderGoodsException
     */
    public function updateBuyerAction(int $buyerId, int $id, int $action)
    {
        $res = $this
            ->advancedWith($this->with['order'])
            ->getArray($id, ['order_id', 'seller_action', 'buyer_action', 'total_price']);

        if ($res) {
            if ($res['buyer_action'] == $action) {
                throw new OrderGoodsException(161013);
            }
            if ($buyerId != $res['order_info']['buyer_id']) {
                throw new OrderGoodsException(161014);
            }
        } else {
            throw new OrderGoodsException(161015);
        }

        if ($action == 1) {
            // 用户收到商品
            $sellerAction = $res['seller_action'];

            # 商家送达，用户方可确定收货
//            if ($sellerAction != 4) {
//                throw new OrderGoodsException(161016);
//            }

            # 用户确认收货，商家的状态更改为已送达（4）
            if ($sellerAction != 4) {
                $this
                    ->removeOption()
                    ->updates($id, [
                        'seller_action' => 4,
                        'update_time' => time()
                    ]);
            }

            // 订单完成
            $orderNo = $res['order_info']['order_no'];
            $Order = new Order;
            $Order->finish($orderNo);
        }

        // 更新买家操作
        $update['buyer_action'] = $action;
        $this->updates($id, $update);
    }

    public function GetOrderGoodsDetail(int $userId,  int $orderGoodsId)
    {
        # user as buyer, needs seller info
        $this
            ->alias('this')
            ->join(['order' => 'o'], 'o.id = this.order_id')
            ->join(['goods' => 'g'], 'g.id = this.goods_id');
        $m = $this->getArray($orderGoodsId, ['o.buyer_id', 'o.seller_id']);

        $User = new User;
        $info = [];
        if ($m['buyer_id'] == $userId) {
            $info['seller_info'] = $User->getArray($userId, ['id', 'telephone']);
        } elseif ($m['seller_id'] == $userId) {
            $info['buyer_info'] = $User->getArray($userId, ['id', 'telephone', 'name']);
        } else {
            throw new OrderException(160003);
        }

        $info['service_tel'] = config('service.tel');

        $res = $this
            ->advancedWith([
                'ImageInfo' => [
                    'field' => 'image_url'
                ]
            ])
            ->getArray($orderGoodsId, [
                'this.id', 'this.create_time', 'order_id', 'address', 'order_no', 'image_id',
                'this.price', 'this.count'
            ]);

        return array_merge($res, $info);
    }

    public function OrderInfo()
    {
        return $this->belongsTo('Order', 'order_id', 'id');
    }

    public function GoodsInfo()
    {
        return $this->belongsTo('Goods', 'goods_id', 'id');
    }

    public function ImageInfo()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    public function SellerInfo()
    {
        return $this->belongsTo('User', 'seller_id', 'id');
    }
}