<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\ModelNotDefined;
use App\Repositories\Contracts\IBase;
use App\Repositories\Criteria\ICriteria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class BaseRepository implements IBase,ICriteria
{
    Protected $model;
    public function __construct()
    {
        $this->model = $this->getModel();
    }
    public function All()
    {
        return $this->model->all();
    }

    public function getModel()
    {
        if(!method_exists($this, 'model')){
            return new ModelNotDefined();
        }

        return app($this->model());
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findWhere($field, $value)
    {
        return $this->model->where($field, $value)->get();
    }

    public function findWhereFirst($field, $value)
    {
        return $this->model->where($field, $value)->first();
    }

    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        return $this->model->findOrFail($id)->update($attributes);
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function criteria(...$args)
    {
        $criteria = Arr::flatten($args);
        foreach ($criteria as $cri) {
            $this->model= $cri->apply($this->model);
        }
        return $this;
    }
}
