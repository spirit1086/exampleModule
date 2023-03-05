<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resumes_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('resume_id');
            $table->unsignedInteger('user_id');
            $table->string('company_title')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('direction_id');
            $table->string('position',500);
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_title',500)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedInteger('is_current_time')->nullable();
            $table->string('qualification',500);
            $table->unsignedInteger('document_id')->nullable();
            $table->longText('publications')->nullable();
            $table->longText('official_duties')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resumes_jobs');
    }
};
