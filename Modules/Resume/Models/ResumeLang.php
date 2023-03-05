<?php

namespace App\Modules\Resume\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeLang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'resumes_lang';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'resume_id'
    ];

    protected $fillable = [
        'resume_id',
        'lang_id',
        'know_lang_id'
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class,'resume_id','id');
    }
}
