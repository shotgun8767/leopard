<?php

namespace app\model;

use app\exception\GoodsException;
use model\BaseModel;
use utility\general\Arrays;

class Goods extends BaseModel
{
    protected $fields = [
        'getRandom' => ['id', 'status', 'name', 'subtitle', 'price', 'image_id', 'category_id', 'seller_id'],
    ];

    protected $with = [
        'getInfo_less' => [
            'ImageInfo' => [
                'field' => 'image_url'
            ],
            'CategoryInfo',
            'SellerInfo' => [
                'field' => ['name', 'avatar_url', 'school_id'],
            ]
        ],
        'getInfo' => [
            'ImageInfo' => [
            'field' => 'image_url'
        ],
            'CategoryInfo' => [
                'field' => 'name'
            ],
            'SellerInfo' => [
                'field' => ['id', 'name', 'avatar_url', 'telephone'],
            ]
        ],

        'getSellerInfo' => [
            'SellerInfo' => [
                'field' => ['id', 'name', 'avatar_url', 'telephone'],
            ]
        ],
    ];

    protected $hidden = ['listorder', 'image_id', 'seller_id', 'category_id', 'subtitle'];

    /**
     * 获取商品信息
     * @param int $id
     * @param int|null $userId
     * @return bool|array
     */
    public function getInfo(int $id, ?int $userId = null)
    {
        $res = $this
            ->advancedWith($this->with['getInfo'])
            ->getArray($id);

        if ($res) {
            $UserLike = new UserLike;

            $res['like'] = $UserLike->getLikeOfGoods($id);
            if ($userId) {
                $where = [
                    'user_id' => $userId,
                    'goods_id' => $id
                ];
                $res['is_like'] = $UserLike->get($where) ? true : false;

                $seller_id = $res['seller_info']['id'];
                $res['is_subs_seller'] = (new UserSubscription)->isSubs($userId, $seller_id);
            }
            return $res;
        } else {
            return false;
        }
    }

    public function getGroupInfo(int $userId, array $ids)
    {
        // 获取商品信息
        $this->removeOption()
            ->whereIn('id', $ids);

        $info = $this
            ->multi()
            ->hidden(['image_id'])
            ->advancedWith($this->with['getInfo_less'])
            ->getArray([], 'getRandom');

        foreach ($info as $key => $value) {
            if ($info[$key]['seller_info'] == null) {
                unset($info[$key]);
                continue;
            }

            $id = $info[$key]['id'];

            /**
             * 获取商品点赞数
             */
            $UserLike = new UserLike;
            $like = $UserLike->getLikeOfGoods($id);
            $isLike = $UserLike->get(['user_id' => $userId, 'goods_id' => $id]);

            $info[$key] = array_merge($info[$key], ['like' => $like, 'is_like' => $isLike ? true : false]);
        }

        return $info;
    }

    /**
     * 随机地获取商品
     * @param int $limit            数量
     * @param int|null $userId      用户id
     * @param int|null $categoryId  类别id
     * @param int|null $exCategoryId
     * @param array $idExclude
     * @param bool $subs
     * @param int $userSchoolId
     * @return mixed
     */
    public function getRandom
    (   int $limit,
        ?int $userId = null,
        ?int $categoryId = null,
        ?int $exCategoryId = null,
        array $idExclude = [],
        bool $subs = false,
        ?int $userSchoolId = 0
    )
    {
        // 不会获得自己发布的商品
        $where = [
            ['seller_id', '<>', $userId]
        ];

        if ($subs) {
            // 仅获取关注对象用户所发布的商品
            $subsUserId = (new UserSubscription)
                ->multi()
                ->getColumn('foreign_id', ['user_id' => $userId]);
            $this->whereIn('seller_id', $subsUserId);
        }

        if ($categoryId) {
            $where['category_id'] = $categoryId;
        }

        if ($categoryId) {
            $this->where('category_id', '<>', $exCategoryId);
        }

        $this->alias('this');
        $strict = config('user.school_restrict.goods');
        if ($strict && $userSchoolId) {
            $this
                ->join(['user' => 'u'],'u.id = this.seller_id')
                ->where(['u.school_id' => $userSchoolId]);
        }

        $goods = $this
            ->multi()
            ->get([], ['this.id']);
        $goodsIds = [];

        foreach ($goods as $model) {
            $goodsIds[] = $model->id;
        }

        // 避免推送重复商品
        $goodsIds = array_diff($goodsIds, $idExclude);

        // 随机抽取id
        $goodsIds = Arrays::random($goodsIds, $limit);
        if (!$goodsIds) return false;

        // 获取商品信息
        $this->removeOption()
            ->whereIn('id', $goodsIds);

        $info = $this
            ->multi()
            ->hidden(['image_id'])
            ->advancedWith($this->with['getInfo_less'])
            ->getArray($where, 'getRandom');

        foreach ($info as $key => $value) {
            if ($info[$key]['seller_info'] == null) {
                unset($info[$key]);
                continue;
            }

            $id = $info[$key]['id'];

            /**
             * 获取商品点赞数
             */
            $UserLike = new UserLike;
            $like = $UserLike->getLikeOfGoods($id);
            $isLike = $UserLike->get(['user_id' => $userId, 'goods_id' => $id]);

            $info[$key] = array_merge($info[$key], ['like' => $like, 'is_like' => $isLike ? true : false]);
        }

        return [
            'ids'   => $goodsIds,
            'info'  => $info
        ];
    }

    /**
     * 获取用户关注商品
     * @param int $userId
     * @param int $page
     * @param int $listRows
     * @return array
     */
    public function getSubs(int $userId, int $page, int $listRows)
    {
        $UserSubscription = new UserSubscription();
        $subsUserId = $UserSubscription->multi()
            ->getColumn('foreign_id', ['user_id' => $userId]);

        $this->order([
            'listorder' => 'DESC',
            'id' => 'DESC'
        ]);
        $info = $this
            ->multi()
            ->page($page, $listRows)
            ->advancedWith($this->with['getInfo_less'])
            ->getArray([
                ['seller_id', 'in', $subsUserId]
            ]);

        foreach ($info as $key => $value) {
            if ($info[$key]['seller_info'] == null) {
                unset($info[$key]);
                continue;
            }
            $id = $info[$key]['id'];

            /**
             * 获取商品点赞数
             */
            $UserLike = new UserLike;
            $like = $UserLike->getLikeOfGoods($id);
            $isLike = $UserLike->get(['user_id' => $userId, 'goods_id' => $id]);

            $info[$key] = array_merge($info[$key], ['like' => $like, 'is_like' => $isLike ? true : false]);
        }

        return $info;
    }

    /**
     * 上传商品
     * @param $userId
     * @param $imageId
     * @param $data
     * @return int
     */
    public function upload(int $userId, int $imageId, array $data)
    {
        $data['image_id'] = $imageId ? $imageId : 0;
        $data['seller_id'] = $userId;

        return $this->inserts($data);
    }

    /**
     * 修改商品信息
     * @param int $id   商品id
     * @param int|null $imageId 图片id
     * @param $data
     * @return mixed
     */
    public function put(int $id, array $data, ?int $imageId = null)
    {
        $this->updatedField = ['image_id', 'name', 'subtitle', 'price', 'description', 'quantity'];
        if ($imageId) {
            $data['image_id'] = $imageId;
        }

        return $this->updates($id, $data);
    }

    /**
     * 获取商家信息
     * @param $id
     * @return array|null
     */
    public function getSellerInfo(int $id)
    {
        return $this
            ->advancedWith($this->with['getSellerInfo'])
            ->getArray($id, ['seller_id'])['seller_info'];
    }

    /**
     * @param int $id
     * @param int $userId 商家
     * @return int
     */
    public function deleteGoods(int $id, int $userId)
    {
        // 判断商品是否商家本人的
        $sellerInfo = $this->getSellerInfo($id);
        if (!key_exists('id', $sellerInfo) || $sellerInfo['id'] != $userId) {
            throw new GoodsException(140007);
        }

        $OrderGoods = new OrderGoods();
        $OrderGoods
            ->removeOption()
            ->alias('this')
            ->join(['order' => 'o'],'o.id = this.order_id');
        $OrderGoods->whereIn('seller_action',  [1, 2, 3]);
        $res = $OrderGoods->getArray(['o.seller_id' => $userId]);

        if ($res) {
            throw new GoodsException(140008);
        } else {
            return $this->softDelete($id);
        }

    }

    public function getUserGoods(int $userId, int $status = 1, int $page = 1, int $listRows = 5)
    {
        $this->order([
            $this->statusField => 'ASC'
        ]);
        return $this
            ->multi()
            ->status($status)
            ->advancedWith([
                'ImageInfo' => [
                    'field' => 'image_url'
                ],
                'CategoryInfo'
            ])
            ->page($page, $listRows)
            ->getArray([
                'seller_id' => $userId
            ]);
    }

    /**
     * 查询商品
     * @param string $query
     * @param int $userSchoolId
     * @param int $page
     * @param int $listRows
     * @return array
     */
    public function search(string $query, ?int $userSchoolId, int $page, int $listRows)
    {
        $this->alias('this');
        $strict = config('user.school_restrict.goods');
        //$strict = false;
        if ($strict && $userSchoolId) {
            $this
                ->join(['user' => 'u'],'u.id = this.seller_id')
                ->where(['u.school_id' => $userSchoolId]);
        }

        $this
            ->where([['this.name', 'like', "%$query%"]])
            ->whereOr([['this.description', 'like', "%$query%"]]);

        return $this
            ->multi()
            ->page($page, $listRows)
            ->order([
                'listorder' => 'DESC'
            ])
            ->advancedWith($this->with['getInfo'])
            ->getArray([], ['this.id', 'this.status', 'this.name', 'price', 'quantity', 'description',
                'image_id', 'category_id', 'seller_id']);
    }

    public function ImageInfo()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }

    public function SellerInfo()
    {
        return $this->belongsTo('User', 'seller_id', 'id');
    }

    public function CategoryInfo()
    {
        return $this->belongsTo('Category', 'category_id', 'id');
    }
}