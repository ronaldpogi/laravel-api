<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class TModel
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', ?int $page = null)
    {
        return $this->model->paginate($perPage, $columns, $pageName, $page);
    }

    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);

        if (! $record) {
            return null;
        }

        $record->update($data); // returns bool, ignore it

        return $record->fresh();        // return updated model
    }

    public function delete($id)
    {
        $record = $this->find($id);
        if ($record) {
            return $record->delete();
        }

        return false;
    }
}
