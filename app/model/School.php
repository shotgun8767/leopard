<?php

namespace app\model;

use model\BaseModel;

class School extends BaseModel
{
    protected $hidden = ['status', 'listorder'];

    public function getAll()
    {
        $this->order([
            'listorder' => 'DESC',
            'id' => 'DESC'
        ]);
        return $this->multi()->getArray();
    }

    public function add(string $name, int $listorder = 0)
    {
        return $this->inserts([
            'name' => $name,
            'listorder' => $listorder
        ], ['name' => $name]);
    }
}