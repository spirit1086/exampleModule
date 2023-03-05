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
        Schema::create('resumes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('iin');
            $table->date('birthday');
            $table->unsignedInteger('gender_id');
            $table->unsignedInteger('nationality_id');
            $table->unsignedInteger('citizenship_id')->default(1);
            $table->string('mobile');
            $table->unsignedInteger('family_status_id');
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('country_area_id');
            $table->unsignedInteger('city_id');
            $table->string('salary_level')->nullable();
            $table->unsignedInteger('is_work_experience')->default(0);
            $table->unsignedInteger('is_nak_job')->default(0);
            $table->mediumText('know_softs')->nullable();
            $table->unsignedInteger('is_training')->default(0); // проходилли повышение квалификации
            $table->unsignedInteger('is_driver_license')->default(0);
            $table->unsignedInteger('is_criminal_info')->default(0);
            $table->unsignedInteger('is_military_service')->default(0);
            $table->mediumText('job_in_companies')->nullable();
            $table->mediumText('about_me')->nullable();
            $table->mediumText('hobby')->nullable();
            $table->unsignedInteger('bussines_trip_id')->nullable();
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
        Schema::dropIfExists('resumes');
    }
};
