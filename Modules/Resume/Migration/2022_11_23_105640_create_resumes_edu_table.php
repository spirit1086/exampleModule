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
        Schema::create('resumes_edu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('resume_id');
            $table->string('edu_title',500);
            $table->string('speciality',500);
            $table->unsignedInteger('edu_type_id');
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_title')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('specialization',500)->nullable();
            $table->string('academic_degree',500)->nullable();
            $table->unsignedInteger('document_id')->nullable();
            $table->string('publications',500)->nullable();
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
        Schema::dropIfExists('resumes_edu');
    }
};
