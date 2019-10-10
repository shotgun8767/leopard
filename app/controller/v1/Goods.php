<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use service\Thumb;
use app\exception\GoodsException;
use app\model\{Goods as model, Image};

class Goods extends BaseApi
{
    const IDS_LOG_NAME = 'goods_ids_log';
    const R_LOG_NAME = 'goods_rand_r';

    /**
     * 根据商品id获取商品信息
     * @param $id
     * @return Package
     */
    public function get($id)
    {
        $userId = $this->getTokenPayload('id');
        $info = (new model)->getInfo($id, $userId);

        return $info ?
            Package::ok('成功获取商品信息', $info) :
            Package::error(GoodsException::class, 140001);
    }

    /**
     * 获取随机商品信息
     * @param $limit
     * @param $subs
     * @param $log
     * @param $no_repeat
     * @param $category_id
     * @param $ex_category_id
     * @return Package
     */
    public function getRandom($limit, $subs = false, $log = 0, $no_repeat = 0, $category_id = 0, $ex_category_id = 0)
    {
        $userId = $this->getTokenPayload('id');
        $userSchoolId = $this->getTokenPayload('sc');
        $idExclude = $no_repeat ? $this->token()->getBind(self::IDS_LOG_NAME, []) : [];
        $res = (new model)->getRandom($limit, $userId, $category_id, $ex_category_id, $idExclude, $subs, $userSchoolId);

        if ($res === false) {
            return Package::error(GoodsException::class, 140002);
        }

        $ids = $res['ids'];
        if ($log) {
            // 缓存用户商品浏览记录
            $this->token()->bindAppend(self::IDS_LOG_NAME, array_values($ids), '86400');
        }

        return Package::ok('成功获取随机商品信息', $res['info']);
    }

    public function getRand(int $limit)
    {
        $page = $this->page();
        $userId = $this->getTokenPayload('id');
        $sc = $this->getTokenPayload('sc');
        $r = $this->token()->getBind(self::R_LOG_NAME);
        $Goods = new model;

        if (!$r) {
            $Goods
                ->alias('this')
                ->join(['user' => 'u'], 'u.id=this.seller_id');

            $ids = $Goods->getColumn(null, ['u.school_id' => $sc]);
            $max = count($ids);
            $in = floor($max / $limit);
            $r = floor(rand(1, $in));

            $this->token()->bind(self::R_LOG_NAME, [
                'in' => $in,
                'r' => $r,
                'ids' => $ids
            ], 28800);
        } else {
            $in = $r['in'];
            $ids = $r['ids'];
            $r = $r['r'];
        }

        $page += $r;
        if ($page > $in) {
            $page -= $in;
            if ($page > $r) {
                return Package::error(GoodsException::class, 140002);
            }
        }

        $sid = [];
        for ($i = $page; $i <= $in * $limit; $i += $in) {
            if (key_exists((int)$i, $ids)) {
                $sid[$i] = $ids[$i];
            }
        }

        $res = $Goods->getGroupInfo($userId, $sid);

        return $res ?
            Package::ok('成功获取随机商品信息', $res) :
            Package::error(GoodsException::class, 140002);
    }

    /**
     * 获取用户关注商家发布的商品
     * @return Package
     */
    public function getSubs()
    {
        $userId = $this->getTokenPayload('id');

        $info = (new model)->getSubs($userId, $this->page(), $this->row());

        return $info ?
            Package::ok('成功获取用户关注商家商品', $info) :
            Package::error(GoodsException::class, 140009);
    }

    /**
     * 上传商品
     * @return Package
     */
    public function upload()
    {
        $userId = $this->getTokenPayload('id');

        // 获取图片
        $ImageModel = new Image;
        $imageId = $ImageModel->upload('image');
        if ($imageId) {
            // 生成缩略图
            $thumb = $ImageModel->thumb($imageId, [Thumb::SQUARE_STANDARD]);
            $imageId = $thumb[0]['thumb_id'];
        }

        // 添加商品
        $id = (new model)->upload($userId, $imageId, $this->param());

        if ($id) {
            return Package::created('成功上传商品', ['id' => $id]);
        } else {
            return Package::error(GoodsException::class, 140004);
        }
    }

    /**
     * 修改商品信息
     * @param $id
     * @return Package
     * @throws GoodsException
     */
    public function edit($id)
    {
        $userId = $this->getTokenPayload('id');

        // 验证是否本人操作
        $Model = new model;
        $sellerInfo = $Model->getSellerInfo($id);
        if (!$sellerId = $sellerInfo['id']??false) {
            throw new GoodsException(140005);
        }
        if ($sellerInfo['id'] != $userId) {
            throw new GoodsException(140006);
        }

        // 上传图片
        $ImageModel = new Image;
        $imageId = $ImageModel->upload('image');
        if ($imageId) {
            // 生成缩略图
            $thumb = $ImageModel->thumb($imageId, [Thumb::SQUARE_STANDARD]);
            $imageId = $thumb[0]['thumb_id'];
        }

        // 修改商品信息
        $Model->put($id, $this->param(), $imageId);
        return Package::ok('成功修改商品信息');
    }

    /**
     * 修改商品封面
     * @return Package|null
     */
    public function editCover()
    {
        return $this->call('edit');
    }

    /**
     * 删除商品
     * @param $id
     * @return Package
     * @throws GoodsException
     */
    public function delete($id)
    {
        $userId = $this->getTokenPayload('id');

        // 验证是否本人操作
        $Model = new model;
        $sellerInfo = $Model->getSellerInfo($id);
        if (!$sellerId = $sellerInfo['id']??false) {
            throw new GoodsException(140005);
        }
        if ($sellerInfo['id'] != $userId) {
            throw new GoodsException(140006);
        }

        $Model->deleteGoods($id, $userId);
        return Package::ok('成功删除商品');
    }

    /**
     * 获取用户发布的商品
     * @return Package
     */
    public function getUserGoods()
    {
        $status = $this->param('status');
        $info = (new model)->getUserGoods($this->getTokenPayload('id'), $status, $this->page(), $this->row());

        return $info ?
            Package::ok('成功获取用户发布的商品', $info) :
            Package::error(GoodsException::class, 140010);
    }

    /**
     * 查询商品
     * @param $query
     * @return Package
     */
    public function search(string $query)
    {
        $userSchoolId = $this->getTokenPayload('sc');
        $info = (new model)->search($query, $userSchoolId,$this->page(), $this->row());

        return $info ?
            Package::ok('成功获取商品信息', $info) :
            Package::error(GoodsException::class, 140009);
    }
}