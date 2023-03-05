<?php
namespace App\Modules\Resume\Repository;

use App\Modules\Resume\Models\Resume;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface InterfaceResumeRepository
{
    /**
     * @param string $orderField
     * @param string $orderDirection
     * @param array $filter
     * @return LengthAwarePaginator
     */
   public function itemsPaginate(string $orderField = 'id', string $orderDirection = 'asc', array $filter = []): LengthAwarePaginator;

    /**
     * @param int $id
     * @return Resume|null
     */
   public function find(int $id): ?Resume;

    /**
     * @param int $id
     * @return Resume|null
     */
    public function findUser(int $id): ?Resume;

    /**
     * @param array $data
     * @param int|null $id
     * @return Resume
     */
   public function save(array $data, ?int $id = null): Resume;

    /**
     * @param int $id
     */
   public function delete(int $id): void;

    /**
     * @param Model $model
     * @return void
     */
   public function removeTraining(Model $model): void;

    /**
     * @param Model $model
     * @param int $id
     * @return Model
     */
    public function relationFind(Model $model, int $id): Model;
}
