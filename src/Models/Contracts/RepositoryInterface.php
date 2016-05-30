<?php

namespace App\Models\Contracts;

/**
 * RepositoryInterface provides the standard functions to be expected of ANY
 * repository.
 */
interface RepositoryInterface {

    public function all($with = [], $orderBy = []);

    public function find($id, $relations = array());

    public function findBy($attribute, $value, $columns = array('*'));

    public function findAllBy($attribute, $value, $columns = array('*'));

    public function findWhere($where, $columns = ['*'], $or = false);

    public function create($attributes = array());

    public function edit($id, $attributes = array());

    public function delete($id);
}