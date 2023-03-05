<?php
namespace App\Modules\Resume\Service;

use App\Modules\Resume\Models\Resume;
use Illuminate\Pagination\LengthAwarePaginator;

interface InterfaceResumeService
{
    /**
     * @param string $orderField
     * @param string $orderDirection
     * @param array $filter
     * @return LengthAwarePaginator
     */
   public function collection(array $filter = [],string $orderField = 'id', string $orderDirection = 'asc'): LengthAwarePaginator;

    /**
     * @param int $id
     * @return Resume|null
     */
   public function getItem(int $id): ?Resume;

    /**
     * @param int $id
     * @return Resume|null
     */
   public function getItemUserResume(int $id): ?Resume;

    /**
     * @param array $data
     * @param int|null $id
     * @return Resume
     */
   public function setItem(array $data, ?int $id = null): Resume;

    /**
     * @param int $id
     */
   public function remove(int $id): void;

    /**
     * @param int $id
     */
   public function removeRelation(int $id, string $relation): void;

   public function generateResume(int $id);
}
