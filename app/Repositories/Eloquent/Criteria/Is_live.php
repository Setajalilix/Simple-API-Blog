<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterias;

class Is_live implements ICriterias
{

    public function apply($model)
    {
       return $model->where('is_live', true);
    }
}
