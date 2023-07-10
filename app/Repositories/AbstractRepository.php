<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;

abstract class AbstractRepository implements RepositoryInterface
{

    const DELETE_FLG = 0;
    /**
     * @var Model
     */
    protected $model;

    /**
     * EloquentRepository constructor.
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Get model
     *
     * @return string
     */
    abstract protected function model();

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set model
     *
     * @throws BindingResolutionException
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->model()
        );
    }

    /**
     * Get All
     *
     * @return Collection|Model[]|mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Get one
     *
     * @param mixed $id
     * @param array $columns
     * @param array $relations
     * @return Model|Collection|static[]|static|null
     */
    public function find($id, $columns = ['*'], $relations = [])
    {
        return $this->model
            ->with($relations)
            ->find($id, $columns)
        ;
    }

    /**
     * Get query builder.
     *
     * @return Builder
     */
    public function builder()
    {
        return $this->model->newQuery();
    }

    /**
     * Create
     *
     * @param array $attributes
     * @return Model|Builder
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update
     *
     * @param int $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, array $attributes)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    /**
     * Delete
     *
     * @param int $id
     * @return bool|mixed
     * @throws \Exception
     */
    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    /**
     * Fill attributes to model
     *
     * @param array $attributes
     * @return Model
     */
    public function fill(array $attributes)
    {
        return $this->model->fill($attributes);
    }

    /**
     * Update or create
     *
     * @param array $attributes
     * @param array $value
     * @return Builder|Model|static
     */
    public function updateOrCreate(array $attributes, array $value)
    {
        return $this->builder()->updateOrCreate($attributes, $value);
    }

    /**
     * Find data by multiple fields
     * @param array $where
     * @param array $columns
     * @param array $relations
     * @return Builder[]|Collection|mixed
     * @throws BindingResolutionException
     */
    public function findWhere(array $where, $columns = ['*'], $relations = [])
    {
        $this->applyConditions($where);

        $model = $this->builder()->select($columns)->with($relations)->get();
        $this->setModel();
        return $model;
    }

    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }

    /**
     * Update tbl with multiple primary key
     *
     * @param array $where
     * @param array $attributes
     * @param bool $lockRecord
     * @return bool
     */
    public function updateMultiKey(array $where, array $attributes, $lockRecord = true)
    {
        if ($lockRecord) {
            return $this->model
                ->lockForUpdate()
                ->where($where)
                ->update($attributes);
        }

        return $this->model
            ->where($where)
            ->update($attributes);
    }

    /**
     * @param $conn
     */
    public function changeConnection($conn)
    {
        $this->model->setConnection($conn);
    }
}
