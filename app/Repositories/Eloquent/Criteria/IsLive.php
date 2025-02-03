<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterias;

class IsLive implements ICriterias
{

    public function apply($model)
    {
       return $model->where('is_live', true);
    }
}
