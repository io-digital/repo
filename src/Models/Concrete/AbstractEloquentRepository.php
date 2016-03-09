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
            ->where($this->model->getTable().'.id', $id)
            ->first();
    }

    public function all($with = [], $orderBy = [])
    {
        $model = $this->make($with, $orderBy);
        return $model->get();
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
