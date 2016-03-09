<?php

namespace App\Models\Contracts;

/**
 * RepositoryInterface provides the standard functions to be expected of ANY
 * repository.
 */
interface RepositoryInterface {

    public function all($with = [], $orderBy = []);

    public function find($id, $relations = array());

    public function create($attributes = array());

    public function edit($id, $attributes = array());

    public function delete($id);
}