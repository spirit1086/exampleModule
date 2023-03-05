<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Resume\Models\Resume;

Route::group(['namespace' => 'App\Modules\Resume\Controllers','prefix'=> 'resumes', 'middleware' => ['locale','jwt']], function()  {
    Route::get('/', 'ResumeController@items')->name('resume_items')->can('read',Resume::class);
    Route::get('/{id}', 'ResumeController@findItem')->name('resume_item')->can('edit',Resume::class);
    Route::get('/user/{resume_user_id}', 'ResumeController@findItemOfUser')->name('resume_item_user')->can('editOwn',Resume::class);
    Route::post('/create', 'ResumeController@createItem')->name('resume_insert')->can('create',Resume::class);
    Route::patch('/{id}', 'ResumeController@updateItem')->name('resume_update')->can('update',Resume::class);
    Route::delete('/{id}', 'ResumeController@removeItem')->name('resume_remove')->can('delete',Resume::class);
    Route::delete('/relation/{id}', 'ResumeController@removeRelation')->name('resume_remove_relation')->can('delete',Resume::class);
    Route::post('/download/{id}', 'ResumeController@downloadResume')->name('resume_download')->can('download_resume',Resume::class);
});
