<?php

namespace App\Repositories\Contracts;
interface IBase {
    public function All();

    public function find($id);
    public function findWhere($field, $value);
    public function findWhereFirst($field, $value);
    public function paginate($perPage = 10);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);

}
