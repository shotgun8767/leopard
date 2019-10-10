<?php

namespace app\model;

use utility\general\Random;
use model\BaseModel;

class Order extends BaseModel
{
    protected $hidden = ['listorder'];

    /**
     * 加载status
     */
    public function initStatus() : void
    {
        $this->_status = [$this->getStatus('VALID')];
    }

    /**
     * 生成新订单
     * @param int $price
     * @param int $count
     * @param int $buyerId
     * @param int $sellerId 为0时，为系统分发订单
     * @return array|int
     */
    public function new(int $price, int $count, int $buyerId, int $sellerId = 0)
    {
        $User = new User;
        $total = $price * $count;

        $originScore = $User->getScore($buyerId);
        if ($originScore < $total) {
            // 积分不足
            return -1;
        }

        $data = [
            'order_no' => $this->getOrderNo(),
            'total_price' => $total,
            'count' => $count,
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
            'create_time' => time()
        ];
        $res = $this->inserts($data, false, 'VALID');

        if ($res) {
            $User->minusScore($buyerId, $total);
            return [
                'id' => $res,
                'order_no' => $data['order_no'],
                'buyer_id' => $buyerId,
                'seller_id' => $sellerId,
                'buyer_balance' => $originScore - $total
            ];
        } else {
            // 新添订单失败
            return -2;
        }
    }

    /**
     * 订单完成，为卖家增加积分
     * @param int $orderNo 唯一订单号
     * @return bool|array
     */
    public function finish($orderNo)
    {
        $where = ['order_no' => $orderNo];
        $data = $this
            ->getArray($where);

        if (!$data) return false;

        $total = $data['total_price'];
        $seller_id = $data['seller_id'];
        $this->updateStatus($data['id'], 'FINISH');

        $return['order_no'] = $orderNo;
        $return['create_time'] = $data['create_time'];
        if ($seller_id) {
            $User = new User;
            $User->addScore($seller_id, $total);
            $return['seller_id'] = $seller_id;
            $return['score_plus'] = $total;
        }

        return $return;
    }

    /**
     * 销毁订单
     * @param $orderNo
     */
    public function destruct($orderNo)
    {
        $where = ['order_no' => $orderNo];
        $data = $this
            ->getArray($where);

        $buyerId = $data['buyer_id'];
        $total = $data['total_price'];

        (new User)->addScore($buyerId, $total);
        $this->softDelete($where);
    }

    /**
     * 生成十六位唯一订单号
     * @return string
     */
    protected function getOrderNo()
    {
        /*
         * (1~3) 三位随机数字
         * (4~13) 下单时间戳x2
         * (14~16) 微秒中间三位(6~4)
         * */
        $r = Random::fixed(3)->includeDigit()->remove(0)->getString();
        return $r . time() * 2 . substr(microtime(), 4,3);
    }
}