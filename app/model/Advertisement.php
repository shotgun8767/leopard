<?php

namespace app\model;

use model\BaseModel;

class Advertisement extends BaseModel
{
    protected $hidden = ['type', 'image_id', 'status', 'listorder'];

    public function getAll()
    {
        return $this
            ->multi()
            ->order([
                'listorder' => 'DESC',
                'id' => 'DESC'
            ])
            ->advancedWith(['ImageInfo' => [
                'field' => ['image_url']
            ]])
            ->getArray();
    }

    public function ImageInfo()
    {
        return $this->belongsTo('image','image_id', 'id');
    }
}