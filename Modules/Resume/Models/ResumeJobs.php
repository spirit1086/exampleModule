<?php

namespace App\Modules\Resume\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeJobs extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'resumes_jobs';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'resume_id'
    ];

    protected $fillable = [
         'resume_id',
         'user_id',
         'is_nak_job',
         'company_title',
         'company_id',
         'direction_id',
         'position',
         'country_id',
         'city_id',
         'city_title',
         'start_date',
         'end_date',
         'is_current_time',
         'publications',
         'official_duties'
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class,'resume_id','id');
    }
}
