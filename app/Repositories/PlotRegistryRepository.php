<?php

namespace App\Repositories;

use App\Models\PlotRegistry;

class PlotRegistryRepository
{
    /**
     * @var PlotRegistry
     */
    protected $model;

    /**
     * PlotRegistryRepository constructor.
     * @param PlotRegistry $model
     */
    public function __construct(PlotRegistry $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $column
     * @param $values
     * @return mixed
     */
    public function whereIn(string $column, $values)
    {
        return $this->model->whereIn($column, $values);
    }

    /**
     * @param array $values
     * @return mixed
     */
    public function create(array $values)
    {
        return $this->model->create($values);
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function searchData(array $ids = [])
    {
        return $this->model->searchData($ids);
    }
}
