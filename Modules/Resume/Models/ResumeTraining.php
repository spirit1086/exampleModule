<?php

namespace App\Modules\Resume\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeTraining extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'resumes_training';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'resume_id'
    ];

    protected $fillable = [
        'resume_id',
        'course_title',
        'org_title',
        'country_id',
        'city_id',
        'city',
        'start_date',
        'end_date',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class,'resume_id','id');
    }
}
