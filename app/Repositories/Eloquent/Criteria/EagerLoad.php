<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterias;

class EagerLoad implements ICriterias
{
    protected $relations;

    public function __construct($relations)
    {
        $this->relations = $relations;
    }

    public function apply($model)
    {
        return $model->with($this->relations);
    }
}
