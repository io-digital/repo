<?php

namespace App\Models\Contracts;

/**
 * RepositoryInterface provides the standard functions to be expected of ANY
 * repository.
 */
interface RepositoryInterface {

    public function all($with = [], $orderBy = [], $columns = ['*']);

    public function find($id, $relations = array());

    public function findBy($attribute, $value, $columns = ['*']);

    public function findAllBy($attribute, $value, $columns = ['*']);

    public function findWhere($where, $columns = ['*'], $or = false);

    public function paginate($perPage = 25, $columns = ['*']);

	public function simplePaginate($limit = null, $columns = ['*']);

    public function create($attributes = []);

    public function edit($id, $attributes = []);

    public function delete($id);
}