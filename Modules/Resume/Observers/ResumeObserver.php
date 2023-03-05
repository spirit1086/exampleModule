<?php

namespace App\Modules\Resume\Observers;

use App\Modules\Jobs\Logger;
use App\Modules\Resume\Models\Resume;
use App\Modules\Resume\Repository\InterfaceResumeRepository;

class ResumeObserver
{
    private const MODEL = 'resume';
    private const SERVICE = 'resumes';
    private const CREATED = 'Создание записи';
    private const SAVING = 'Обновление записи';
    private const DELETED = 'Удаление записи';

    private InterfaceResumeRepository $resumeRepository;

    public function __construct(InterfaceResumeRepository $resumeRepository)
    {
        $this->resumeRepository = $resumeRepository;
    }

    /**
     * Handle the Role "created" event.
     *
     * @param Resume $resume
     * @return void
     */
    public function created(Resume $resume)
    {
        $userId = request()->input('auth_user_id','0');
        $username = request()->input('username','seed');
        Logger::dispatch(['service_name' => self::SERVICE,
            'model' => self::MODEL,
            'model_id' => $resume->id,
            'user_id' => $userId,
            'username' => $username,
            'description' => self::CREATED,
            'code' => ['old' => [], 'new' => $resume]
        ]);
    }

    /**
     * Handle the Country "created" event.
     *
     * @param Resume $resume
     * @return void
     */
    public function saving(Resume $resume)
    {
        $userId = request()->input('auth_user_id',0);
        $username = request()->input('username','seed');
        if (isset($resume->id) && $userId > 0) {
            $beforeItemData = $this->resumeRepository->find($resume->id)->toArray();
            Logger::dispatch(['service_name' => self::SERVICE,
                'model' => self::MODEL,
                'model_id' => $resume->id,
                'user_id' => $userId,
                'username' => $username,
                'description' => self::SAVING,
                'code' => ['old' => $beforeItemData, 'new' => $resume]
            ]);
        }
    }

    /**
     * @param Resume $resume
     * @return void
     */
    public function deleted(Resume $resume)
    {
        $resume->jobs()->delete();
        $resume->educations()->delete();
        $resume->languages()->delete();
        $resume->trainings()->delete();
        $userId = request()->input('auth_user_id',0);
        $username = request()->input('username','seed');
        Logger::dispatch(['service_name' => self::SERVICE,
            'model' => self::MODEL,
            'model_id' => $resume->id,
            'user_id' => $userId,
            'username' => $username,
            'description' => self::DELETED]);
    }
}
