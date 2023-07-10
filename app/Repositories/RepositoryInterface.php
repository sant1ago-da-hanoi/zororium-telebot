<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

interface RepositoryInterface
{
    /**
     * Get all
     * @return mixed
     */
    public function all();

    /**
     * Get one
     * @param mixed $id
     * @param array $columns
     * @param array $relations
     * @return mixed
     */
    public function find($id, $columns = ['*'], $relations = []);

    /**
     * Get query builder
     * @return Builder
     */
    public function builder();

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update
     * @param int $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     * Delete
     * @param int $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Fill attributes to model
     *
     * @param array $attributes
     * @return mixed
     */
    public function fill(array $attributes);

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @param array $relations
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*'], $relations = []);

    /**
     * Update tbl with multiple primary key
     *
     * @param array $where
     * @param array $attribute
     * @return mixed
     */
    public function updateMultiKey(array $where, array $attribute);
}
