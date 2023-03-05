<?php

namespace App\Modules\Resume\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory;
    use SoftDeletes;

    CONST GENDER_FEMALE = 1;
    CONST GENDER_MALE = 2;
    CONST GENDERS = [
        self::GENDER_FEMALE => 'Женский',
        self::GENDER_MALE => 'Мужкой'
    ];

    CONST BIRTHDAY_GENDER = [
        self::GENDER_FEMALE => 'родилась',
        self::GENDER_MALE => 'родился'
    ];

    CONST BUSSINES_TRIP = [
      1 => 'Готов к командировкам',
      2 => 'Готов к редким командировкам',
      3 => 'Не готов к командировкам'
    ];

    CONST DRIVER_LICENSE = [
        0 => 'Нет',
        1 => 'Да'
    ];

    CONST CRIMINAL_INFO = [
        0 => 'Нет',
        1 => 'Да'
    ];

    CONST MILITARY_INFO = [
        0 => 'Нет',
        1 => 'Да'
    ];

    protected $fillable = [
        'user_id',
        'iin',
        'birthday',
        'gender_id',
        'nationality_id',
        'citizenship_id',
        'mobile',
        'family_status_id',
        'country_id',
        'country_area_id',
        'city_id',
        'salary_level',
        'is_work_experience',
        'know_softs',
        'is_training',
        'is_driver_license',
        'is_criminal_info',
        'is_military_service',
        'job_in_companies',
        'about_me',
        'hobby',
        'bussines_trip_id',
        'hh_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'resume_id'
    ];

    public function jobs()
    {
        return $this->hasMany(ResumeJobs::class,'resume_id','id')->orderByDesc('id');
    }

    public function educations()
    {
        return $this->hasMany(ResumeEdu::class,'resume_id','id')->orderByDesc('id');
    }

    public function languages()
    {
        return $this->hasMany(ResumeLang::class,'resume_id','id')->orderByDesc('id');
    }

    public function trainings()
    {
        return $this->hasMany(ResumeTraining::class,'resume_id','id')->orderByDesc('id');
    }
}
