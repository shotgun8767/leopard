<?php

namespace app\model;

use model\BaseModel;

/**
 * 种类
 * Class Category
 * @package app\model
 */
class Category extends BaseModel
{
    protected $hidden = ['image_id', 'status', 'listorder'];

    protected $with = [
        'getInfo' => [
            'ImageInfo' => ['image_url']
        ],
    ];

    /**
     * 添加种类
     * @param string $name
     * @param int $imageId      图片id
     * @param int $listorder    序列编号（优先程度）
     * @return false|int
     */
    public function add(string $name, int $imageId, int $listorder)
    {
        $data = [
            'name' => $name,
            'image_id' => $imageId,
            'listorder' => $listorder
        ];
        return $this->get(['name' => $name]) ? false : $this->inserts($data);
    }

    /**
     * 获取全部种类
     * @return array|null
     */
    public function getAll()
    {
        $with = ['ImageInfo' => [
            'field' => ['image_url']
        ]];

        $this
            ->multi()
            ->advancedWith($with)
            ->order(['listorder' => 'DESC', 'id' => 'DESC']);

        return $this->getArray();
    }

    /**
     * 是否存在同名种类
     * @param $name
     * @return array|\think\Collection|\think\Model|\think\model\Collection|null
     */
    public function existName(string $name)
    {
        return $this->get(['name' => $name]);
    }

    /**
     * 根据种类的名字获取种类
     * @param string $name
     * @return array|null
     */
    public function getByName(string $name)
    {
        return $this->getArray(['name' => $name]);
    }

    public function ImageInfo()
    {
        return $this->belongsTo('Image', 'image_id', 'id');
    }
}