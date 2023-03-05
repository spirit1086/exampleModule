<?php

namespace App\Modules\Resume\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeEdu extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'resumes_edu';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'resume_id'
    ];

    protected $fillable = [
        'resume_id',
        'edu_title',
        'speciality',
        'edu_type_id',
        'country_id',
        'city_id',
        'city_title',
        'start_date',
        'end_date',
        'specialization',
        'academic_degree',
        'document_id',
        'publications'
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class,'resume_id','id');
    }
}
