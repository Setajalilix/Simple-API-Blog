<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterias;

class WithTrashed implements ICriterias
{

    public function apply($model)
    {
       return $model->withTrashed();
    }
}
