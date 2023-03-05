<?php
namespace App\Modules\Resume\Repository;

use App\Modules\Resume\Models\Resume;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ResumeRepository implements InterfaceResumeRepository
{
    /**
     * @param string $orderField
     * @param string $orderDirection
     * @param array $filter
     * @return LengthAwarePaginator
     */
   public function itemsPaginate(string $orderField = 'id', string $orderDirection = 'asc', array $filter = []): LengthAwarePaginator
   {
      return Resume::select('id','user_id','mobile','salary_level','updated_at','city_id','birthday')
                    ->with(['jobs' => function ($query) {
                                    $query->orderBy('id','desc')->limit(1);
                            }])
                   ->orderBy($orderField, $orderDirection)->paginate();
   }

    /**
     * @param int $id
     * @return Resume|null
     */
   public function find(int $id): ?Resume
   {
       return Resume::with(['jobs','educations','languages','trainings'])->findOrFail($id);
   }

    /**
     * @param int $id
     * @return Resume|null
     */
    public function findUser(int $id): ?Resume
    {
        return Resume::with(['jobs','educations','languages','trainings'])->where('user_id','=', $id)->first();
    }

    /**
     * @param array $data
     * @param int|null $id
     * @return Resume
     */
   public function save(array $data, ?int $id = null): Resume
   {
        return Resume::updateOrCreate(['id' => $id], $data);
   }

    /**
     * @param int $id
     * @return void
     */
   public function delete(int $id): void
   {
       Resume::where('id', '=', $id)->delete();
   }

    /**
     * @param Model $model
     * @param int $id
     * @return Model
     */
   public function relationFind(Model $model,int $id): Model
   {
      return $model::findOrFail($id);
   }

    /**
     * @param Model $model
     */
   public function removeTraining(Model $model): void
   {
      $model->destroy($model->id);
   }
}
