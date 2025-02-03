<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterias;

class ForUser implements ICriterias
{
    protected $id;
    public function __construct($id){
        $this->id = $id;
    }
    public function apply($model)
    {
        return $model->where('user_id', $this->id);
    }
}
