<?php
namespace App\Modules\Resume\Service;

use App\Helpers\Dates\Dates;
use App\Helpers\Minio\Minio;
use App\Modules\Resume\Models\Resume;
use App\Modules\Resume\Models\ResumeEdu;
use App\Modules\Resume\Models\ResumeJobs;
use App\Modules\Resume\Models\ResumeLang;
use App\Modules\Resume\Models\ResumeTraining;
use App\Modules\Resume\Repository\InterfaceResumeRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ResumeService implements InterfaceResumeService
{
   private InterfaceResumeRepository $resumeRepository;
   private PredisConnection $redisConnection;
   private string $locale;
   private array $nationalities, $organizations, $countries, $country_areas, $cities, $education_types, $documents,
                 $languages, $know_lang, $users, $professional_area;

   private Minio $minio;
    /**
     * ResumeService constructor.
     * @param InterfaceResumeRepository $resumeRepository
     */
   public function __construct(InterfaceResumeRepository $resumeRepository, PredisConnection $redisConnection)
   {
        $blank_redis_key = json_encode([]);
        $this->resumeRepository = $resumeRepository;
        $this->redisConnection = $redisConnection;
        $this->locale = App::getLocale();
        $this->nationalities = json_decode($this->redisConnection->client()->get('nationalities') ?? $blank_redis_key, true);
        $this->organizations = json_decode($this->redisConnection->client()->get('organizations') ?? $blank_redis_key, true);
        $this->countries = json_decode($this->redisConnection->client()->get('countries') ?? $blank_redis_key, true);
        $this->country_areas = json_decode($this->redisConnection->client()->get('country_areas') ?? $blank_redis_key, true);
        $this->cities = json_decode($this->redisConnection->client()->get('cities') ?? $blank_redis_key, true);
        $this->education_types = json_decode($this->redisConnection->client()->get('education_type') ?? $blank_redis_key, true);
        $this->documents = json_decode($this->redisConnection->client()->get('documents') ?? $blank_redis_key, true);
        $this->professional_area = json_decode($this->redisConnection->client()->get('professional_area') ?? $blank_redis_key, true);
        $this->languages = json_decode($this->redisConnection->client()->get('languages') ?? $blank_redis_key, true);
        $this->know_lang = json_decode($this->redisConnection->client()->get('know_lang') ?? $blank_redis_key, true);
        $this->users = json_decode($this->redisConnection->client()->get('users') ?? $blank_redis_key, true);
        $this->minio = new Minio();
   }

    /**
     * @param string $orderField
     * @param string $orderDirection
     * @param array $filter
     * @return LengthAwarePaginator
     */
   public function collection(array $filter = [], string $orderField = 'id', string $orderDirection = 'asc'): LengthAwarePaginator
   {
       $currentDate = date('Y-m-d');
       $resumes = $this->resumeRepository->itemsPaginate($orderField, $orderDirection, $filter);
       foreach ($resumes as $resume) {
         $user = $this->users[$resume->user_id] ?? null;
         $resume->fio = $user['fio'] ?? null;
         $resume->age = Dates::differenceYear($resume->birthday,$currentDate);
         $resume->email = $user['email'] ?? null;
         $resume->city = $this->cities[$resume->city_id][$this->locale] ?? null;
         $resume->job_expirience_sum = 0;
         foreach ($resume->jobs as $job) {
             $job->company = $this->organizations[$job->company_id][$this->locale] ?? null;
             $job->direction = $this->professional_area[$job->direction_id][$this->locale] ?? null;
             $job->country = $this->countries[$job->country_id][$this->locale] ?? null;
             $job->city = $this->cities[$job->city_id][$this->locale] ?? null;
             $job->end_date = $job->end_date ?? $currentDate;
             $resume->job_expirience_sum+= Dates::differenceYear($job->start_date,$job->end_date);
         }
         $resume->job_expirience_sum = Dates::declOfNum($resume->job_expirience_sum, array('год', 'года', 'лет'));
         $resume->updated = date('Y-m-d',strtotime($resume->updated_at));
         $resume->avatar = $user['avatar'] ?? null;
       }
       return $resumes;
   }

    /**
     * @param int $id
     * @return Resume|null
     */
   public function getItem(int $id): ?Resume
   {
       $resume = $this->resumeRepository->find($id);
       $user = $this->users[$resume->user_id] ?? null;
       $resume->nationality = $this->nationalities[$resume->nationality_id][$this->locale] ?? null;
       $resume->citizenship = $this->countries[$resume->citizenship_id][$this->locale] ?? null;
       $resume->country = $this->countries[$resume->country_id][$this->locale] ?? null;
       $resume->country_area = $this->country_areas[$resume->country_area_id][$this->locale] ?? null;
       $resume->city = $this->cities[$resume->city_id][$this->locale] ?? null;
       $resume->gender = Resume::GENDERS[$resume->gender_id];
       $resume->avatar = $user['avatar'] ?? null;
       foreach ($resume->educations as $education) {
           $education->edu_type = $this->education_types[$education->edu_type_id][$this->locale] ?? null;
           $education->country = $this->countries[$education->country_id][$this->locale] ?? null;
           $education->city = $this->cities[$education->city_id][$this->locale] ?? null;
           $education->document = $this->documents[$education->document_id][$this->locale] ?? null;
       }
       foreach ($resume->jobs as $job) {
           $job->company = $this->organizations[$job->company_id][$this->locale] ?? null;
           $job->direction = $this->professional_area[$job->direction_id][$this->locale] ?? null;
           $job->country = $this->countries[$job->country_id][$this->locale] ?? null;
           $job->city = $this->cities[$job->city_id][$this->locale] ?? null;
       }
       foreach ($resume->languages as $language) {
           $language->language = $this->languages[$language->lang_id][$this->locale] ?? null;
           $language->know_lang = $this->know_lang[$language->know_lang_id][$this->locale] ?? null;
       }
       foreach ($resume->trainings as $training) {
           $training->country = $this->countries[$training->country_id][$this->locale] ?? null;
       }
       return $resume;
   }

    /**
     * @param int $id
     * @return Resume|null
     */
    public function getItemUserResume(int $id): ?Resume
    {
        $resumeUser = $this->resumeRepository->findUser($id);
        if (!$resumeUser) {
            abort(404);
        }
        foreach ($resumeUser->educations as $education) {
            $education->edu_type = $this->education_types[$education->edu_type_id][$this->locale] ?? null;
            $education->country = $this->countries[$education->country_id][$this->locale] ?? null;
            $education->city = $this->cities[$education->city_id][$this->locale] ?? null;
            $education->document = $this->documents[$education->document_id][$this->locale] ?? null;
        }
        foreach ($resumeUser->jobs as $job) {
            $job->company = $this->organizations[$job->company_id][$this->locale] ?? null;
            $job->direction = $this->professional_area[$job->direction_id][$this->locale] ?? null;
            $job->country = $this->countries[$job->country_id][$this->locale] ?? null;
            $job->city = $this->cities[$job->city_id][$this->locale] ?? null;
        }
        foreach ($resumeUser->languages as $language) {
            $language->language = $this->languages[$language->lang_id][$this->locale] ?? null;
            $language->know_lang = $this->know_lang[$language->know_lang_id][$this->locale] ?? null;
        }
        foreach ($resumeUser->trainings as $training) {
            $training->country = $this->countries[$training->country_id][$this->locale] ?? null;
        }
        return $resumeUser;
    }

    /**
     * @param array $data
     * @param int|null $id
     * @return Resume
     */
   public function setItem(array $data, ?int $id = null): Resume
   {
       if ($id) {
         $this->getItem($id);
       }
       $resume = new Resume();
       DB::transaction(function () use ($data, $id, &$resume) {
           $resume = $this->resumeRepository->save($data, $id);
           $job = $data['jobs'] ?? null;
           $edu = $data['educations'] ?? null;
           $lang = $data['languages'] ?? null;
           $trainings = $data['trainings'] ?? null;
           $user_id = $data['user_id'];
           if ($job) {
               $this->setJobs($resume, $job, $user_id);
           }
           if ($edu) {
               $this->setEdu($resume, $edu);
           }
           if ($lang) {
               $this->setLang($resume, $lang);
           }
           if ($trainings) {
               $this->setTraining($resume, $trainings);
           }
       });
       return $this->getItem($resume->id);
   }

    /**
     * @param Resume $resume
     * @param array $jobs
     * @param int $user_id
     */
   private function setJobs(Resume $resume,array $jobs, int $user_id): void
   {
           $count = count($jobs);
           $resume->jobs()->forceDelete();
           for ($i = 0; $i < $count; $i++) {
               $userJobs[$i] = new ResumeJobs([
                   'user_id' => $user_id,
                   'is_nak_job' => $jobs[$i]['is_nak_job'],
                   'direction_id' => $jobs[$i]['direction_id'],
                   'position' => $jobs[$i]['position'],
                   'country_id' => $jobs[$i]['country_id'],
                   'city_id' => $jobs[$i]['city_id'],
                   'start_date' => date('Y-m-d', strtotime($jobs[$i]['start_date'])),
                   'end_date' => isset($jobs[$i]['end_date']) ? date('Y-m-d', strtotime($jobs[$i]['end_date'])) : null,
                   'is_current_time' => $jobs[$i]['is_current_time'] ?? null,
                   'qualification' => $jobs[$i]['qualification'] ?? null,
                   'document_id' => $jobs[$i]['document_id'] ?? null,
                   'publications' => $jobs[$i]['publications'] ?? null,
                   'official_duties' => $jobs[$i]['official_duties'] ?? null
               ]);

               if (isset($jobs[$i]['company_id'])) {
                   $userJobs[$i]['company_id'] = $jobs[$i]['company_id'];
               }
               if (isset($jobs[$i]['company_title'])) {
                   $userJobs[$i]['company_title'] = $jobs[$i]['company_title'];
               }
               if (isset($jobs[$i]['city_title'])) {
                   $userJobs[$i]['city_title'] = $jobs[$i]['city_title'];
               }
           }
           if (isset($userJobs)) {
               $resume->jobs()->saveMany($userJobs);
           }
   }

    /**
     * @param Resume $resume
     * @param array $edu
     */
   private function setEdu(Resume $resume, array $edu): void
   {
       $count = count($edu);
       $resume->educations()->forceDelete();
       for ($i = 0; $i < $count; $i++) {
           $userEdu[] = new ResumeEdu([
               'edu_title' => $edu[$i]['edu_title'],
               'speciality' => $edu[$i]['speciality'],
               'edu_type_id' => $edu[$i]['edu_type_id'],
               'country_id' => $edu[$i]['country_id'],
               'city_id' => $edu[$i]['city_id'],
               'city_title' => $edu[$i]['city_title'] ?? null,
               'start_date' => $edu[$i]['start_date'],
               'end_date' => $edu[$i]['end_date'],
               'specialization' => $edu[$i]['specialization'] ?? null,
               'academic_degree' => $edu[$i]['academic_degree']  ?? null,
               'document_id' => $edu[$i]['document_id']  ?? null,
               'publications' => $edu[$i]['publications']  ?? null
           ]);
       }
       if (isset($userEdu)) {
           $resume->educations()->saveMany($userEdu);
       }
   }

    /**
     * @param Resume $resume
     * @param array $lang
     */
   private function setLang(Resume $resume, array $lang): void
   {
       $count = count($lang);
       $resume->languages()->forceDelete();
       for ($i = 0; $i < $count; $i++) {
           $userLang[] = new ResumeLang([
               'lang_id' => $lang[$i]['lang_id'],
               'know_lang_id' => $lang[$i]['know_lang_id']
           ]);
       }
       if (isset($userLang)) {
           $resume->languages()->saveMany($userLang);
       }
   }

    /**
     * @param Resume $resume
     * @param array $trainings
     */
   private function setTraining(Resume $resume, array $trainings): void
    {
        $count = count($trainings);
        $resume->trainings()->forceDelete();
        for ($i = 0; $i < $count; $i++) {
            $userTrainings[] = new ResumeTraining([
                'course_title' => $trainings[$i]['course_title'] ?? null,
                'org_title' => $trainings[$i]['org_title'] ?? null,
                'country_id' => $trainings[$i]['country_id'] ?? null,
                'city_id' => $trainings[$i]['city_id'] ?? null,
                'city' => $trainings[$i]['city'] ?? null,
                'start_date' => $trainings[$i]['start_date'] ?? null,
                'end_date' => $trainings[$i]['end_date'] ?? null,
            ]);
        }
        if (isset($userTrainings)) {
            $resume->trainings()->saveMany($userTrainings);
        }
    }

    /**
     * @param int $id
     * @return void
     */
   public function remove(int $id): void
   {
       $this->getItem($id);
       $this->resumeRepository->delete($id);
   }

    /**
     * @param int $id
     * @param string $relation
     * @return void
     */
   public function removeRelation(int $id, string $relation): void
   {
       switch ($relation) {
           case 'educations':
               $object = new ResumeEdu();
               break;
           case 'jobs':
               $object = new ResumeJobs();
               break;
           case 'languages':
               $object = new ResumeLang();
               break;
           case 'trainings':
               $object = new ResumeTraining();
               break;
       }
       $model = $this->resumeRepository->relationFind($object, $id);
       $this->resumeRepository->removeTraining($model);
   }

   public function generateResume(int $resumeId)
   {
      $resume = $this->resumeRepository->find($resumeId);
      $currentDate = date('Y-m-d');
      $userFio = $this->users[$resume->user_id]['fio'] ?? null;
      $resume->fio = $userFio;
      $age = Dates::differenceYear($resume->birthday,$currentDate);
      $resume->age = Dates::declOfNum($age, array('год', 'года', 'лет'));
      $day = date('d',strtotime($resume->birthday));
      $month = date('m',strtotime($resume->birthday));
      $year = date('Y',strtotime($resume->birthday));
      $resume->birthday_string = Resume::BIRTHDAY_GENDER[$resume->gender_id] . ' ' . $day . ' '. Dates::MONTHS[$month] . ' ' .$year;
      $resume->gender = Resume::GENDERS[$resume->gender_id];
      $resume->email = $this->users[$resume->user_id]['email'] ?? null;
      $resume->city = $this->cities[$resume->city_id][$this->locale] ?? null;
      $resume->citizenship =  $this->countries[$resume->citizenship_id][$this->locale];
      $resume->bussinesTrip = Resume::BUSSINES_TRIP[$resume->bussines_trip_id] ?? null;
      $resume->avatar = $this->users[$resume->user_id]['avatar'] ?? null;
      $resume->experience = 0;
      foreach($resume->jobs as $job) {
          $endDate = $job->is_current_time ? 'по настоящее время' : Dates::MONTHSImp[date('m',strtotime($job->end_date))]
                                                                   .' '. date('Y',strtotime($job->end_date));
          $job->dates = Dates::MONTHSImp[date('m',strtotime($job->start_date))]
                        .' '. date('Y',strtotime($job->start_date)) . ' - ' .$endDate;

          $job->company = $this->organizations[$job->company_id][$this->locale] ?? $job->company_title;
          $job->country = $this->countries[$job->country_id][$this->locale] ?? null;
          $job->city = $this->cities[$job->city_id][$this->locale] ?? $job->city_title;
          $endDate = $job->is_current_time ? date('Y-m-d') : $job->end_date;
          $resume->experience+= Dates::differenceMonths($job->start_date, $endDate);
      }
      $resume->experience = Dates::expirienceJob($resume->experience);
      foreach ($resume->educations as $education) {
           $education->edu_type = $this->education_types[$education->edu_type_id][$this->locale] ?? null;
           $education->country = $this->countries[$education->country_id][$this->locale] ?? null;
           $education->city = $this->cities[$education->city_id][$this->locale] ?? null;
           $education->document = $this->documents[$education->document_id][$this->locale] ?? null;
           $endDate = $job->is_current_time ? 'по настоящее время' : Dates::MONTHSImp[date('m',strtotime($education->end_date))]
              .' '. date('Y',strtotime($education->end_date));
           $education->dates = Dates::MONTHSImp[date('m',strtotime($education->start_date))]
              .' '. date('Y',strtotime($education->start_date)) . ' - ' .$endDate;
       }
       foreach ($resume->languages as $language) {
           $language->language = $this->languages[$language->lang_id][$this->locale] ?? null;
           $language->know_lang = $this->know_lang[$language->know_lang_id][$this->locale] ?? null;
       }
      $resume->driverLicense = Resume::DRIVER_LICENSE[$resume->is_driver_license];
      $resume->criminalInfo = Resume::CRIMINAL_INFO[$resume->is_criminal_info];
      $resume->militaryService = Resume::MILITARY_INFO[$resume->is_military_service];
      $pdf = Pdf::loadView('Resume::resume', ['resume' => $resume]);
      $pdf->set_option('fontDir', base_path() . '/Modules/Resume/Views/assets/fonts');
      $resumeFilePdf = 'resume-' . str_replace(' ','_', $userFio). '.pdf';
      $resumePath = base_path() . '/public/usersResume/';
      $pdf->save($resumePath . $resumeFilePdf);
      $fileUrl = $this->minio->setFile($resumeFilePdf,'resumeId_' . $resume->id, $resumePath);
      File::delete($resumePath . $resumeFilePdf);
      return $fileUrl;
   }
}
