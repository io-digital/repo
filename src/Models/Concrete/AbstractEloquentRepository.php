<?php

namespace App\Models\Concrete;

use App\Models\Contracts\RepositoryInterface;

abstract class AbstractEloquentRepository implements RepositoryInterface
{
    //public $relationTree = array();

    public function make(array $with = array(), array $orderBy = array())
    {
        if(!empty($with)){
            $this->model = $this->model->with($with);
        }

        if(!empty($orderBy)){
            foreach($orderBy as $col => $dir){
                $this->model = $this->model->orderBy($col, $dir);
            }
        }

        return $this->model;
    }

    public function find($id, $relations = array()){

        return $this->make($relations)
            ->where('id', $id)
            ->first();
    }

    /**
     * $this->model->findBy('title', $title);
     *
     */
    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * $this->model->findAllBy('author_id', $author_id);
     *
     */
    public function findAllBy($attribute, $value, $columns = ['*'])
    {
        return $this->model->where($attribute, '=', $value)->get($columns);
    }

    /**
     * $this->model->findWhere(
     *      ['name' => $name,
     *      ['year','>',$year]
     * ]);
     *
     */
    public function findWhere($where, $columns = ['*'], $or = false)
    {
        $model = $this->model;
        foreach ($where as $field => $value) {
            if ($value instanceof \Closure) {
                $model = (!$or)
                    ? $model->where($value)
                    : $model->orWhere($value);
            } elseif (is_array($value)) {
                if (count($value) === 3) {
                    list($field, $operator, $search) = $value;
                    $model = (!$or)
                        ? $model->where($field, $operator, $search)
                        : $model->orWhere($field, $operator, $search);
                } elseif (count($value) === 2) {
                    list($field, $search) = $value;
                    $model = (!$or)
                        ? $model->where($field, '=', $search)
                        : $model->orWhere($field, '=', $search);
                }
            } else {
                $model = (!$or)
                    ? $model->where($field, '=', $value)
                    : $model->orWhere($field, '=', $value);
            }
        }
        return $model->get($columns);
    }

    public function paginate($perPage = 25, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function simplePaginate($perPage = 25, $columns = ['*'])
    {
            return $this->model->simplePaginate($perPage, $columns);
    }

    public function all($with = [], $orderBy = [], $columns = ['*'])
    {
        $model = $this->make($with, $orderBy);
        return $model->get($columns);
    }

    public function create($attributes = array()){
        return $this->model->create($attributes);
    }

    public function edit($id, $attributes = array()){

        $obj = $this->model->find($id);

        if(!$obj){
            return false;
        }

        $obj->edit($attributes);

        return $obj;
    }

    public function delete($id)
    {
        $obj = $this->model->find($id);

        if(!$obj){
            return false;
        }

        return $obj->delete();
    }
}
