<?php

namespace App\Modules\Resume\Requests;

use App\Modules\Resume\Rules\CollectionRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResumeFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'iin' => 'required',
            'birthday' => 'required',
            'gender_id' => 'required',
            'nationality_id' => 'required',
            'citizenship_id' => 'required',
            'mobile' => 'required',
            'family_status_id' => 'required',
            'country_id' => 'required',
            'country_area_id' => 'required',
            'city_id' => 'required',
            'is_criminal_info' => 'required',
            'educations.*' => [new CollectionRule($this->fields(), $this->validateErrorMessage(),'educations')],
            'educations.*.city_title' => 'required_if:educations.*.city_id,-1000',
            'jobs.*' => [new CollectionRule($this->fields(), $this->validateErrorMessage(),'jobs')],
            'jobs.*.city_title' => 'required_if:jobs.*.city_id,-1000',
            'jobs.*.company_title' => 'required_if:jobs.*.is_nak_job,0',
            'jobs.*.company_id' => 'required_if:jobs.*.is_nak_job,1',
            'jobs.*.end_date' => 'sometimes|required_if:is_current_time,0',
            'jobs.*.is_current_time' => 'sometimes|required_if:end_date,0',
            'languages.*' => [new CollectionRule($this->fields(), $this->validateErrorMessage(),'languages')],
        ];
    }

    public function messages()
    {
        return [
            'iin.required' => __('Resume::validation.required'),
            'birthday.required' => __('Resume::validation.required'),
            'gender_id.required' => __('Resume::validation.required'),
            'nationality_id.required' => __('Resume::validation.required'),
            'citizenship_id.required' => __('Resume::validation.required'),
            'mobile.required' => __('Resume::validation.required'),
            'family_status_id.required' => __('Resume::validation.required'),
            'country_id.required' => __('Resume::validation.required'),
            'country_area_id.required' => __('Resume::validation.required'),
            'city_id.required' => __('Resume::validation.required'),
            'is_criminal_info.required' => __('Resume::validation.required'),
            'educations.*.edu_title.required' => __('Resume::validation.required'),
            'educations.*.speciality.required' => __('Resume::validation.required'),
            'educations.*.city_title.required_if' => __('Resume::validation.required'),
            'jobs.*' => __('Resume::validation.required'),
            'jobs.*.company_id.required_if' => __('Resume::validation.required_if'),
            'jobs.*.city_title.required_if' => __('Resume::validation.required_if'),
            'languages.*.lang_id.required' => __('Resume::validation.required'),
            'languages.*.know_lang_id.required' => __('Resume::validation.required'),
        ];
    }

    public function attributes()
    {
        return [
            'iin' => __('Resume::fields.iin'),
            'jobs.*.company_id' => __('Resume::fields.jobs_company_id'),
            'jobs.*.city_title' => __('Resume::fields.jobs_city_title'),
            'jobs.*.company_title' => __('Resume::fields.jobs_company_title'),
            'educations.*.city_title' => __('Resume::fields.educations_city_title'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ],400));
    }

    private function fields()
    {
        return  [
            'educations' => [
                'edu_title',
                'speciality',
                'edu_type_id',
                'country_id',
                'city_id',
                'start_date',
                'end_date'
            ],
            'jobs' => [
                'direction_id',
                'position',
                'country_id',
                'city_id',
                'start_date'
            ],
            'languages' => [
                'lang_id',
                'know_lang_id'
            ]
        ];
    }

    private function validateErrorMessage()
    {
        return [
            'educations' => [
                   'edu_title' => __('Resume::validation.field') . ' ' . __('Resume::fields.educations_title') . ' ' . __('Resume::validation.custom_required'),
                   'speciality' => __('Resume::validation.field') . ' ' . __('Resume::fields.educations_speciality') . ' ' . __('Resume::validation.custom_required'),
                   'edu_type_id' => __('Resume::validation.field') . ' ' . __('Resume::fields.educations_edu_type_id') . ' ' . __('Resume::validation.custom_required'),
                   'country_id' => __('Resume::validation.field') . ' ' . __('Resume::fields.educations_country_id') . ' ' . __('Resume::validation.custom_required'),
                   'city_id' => __('Resume::validation.field') . ' ' . __('Resume::fields.educations_city_id') . ' ' . __('Resume::validation.custom_required'),
                   'start_date' => __('Resume::validation.field') . ' ' . __('Resume::fields.educations_start_date') . ' ' . __('Resume::validation.custom_required'),
                   'end_date' => __('Resume::validation.field') . ' ' . __('Resume::fields.educations_end_date') . ' ' . __('Resume::validation.custom_required')
            ],
            'jobs' => [
                   'direction_id' => __('Resume::validation.field') . ' ' . __('Resume::fields.jobs_direction_id') . ' ' . __('Resume::validation.custom_required'),
                   'position' => __('Resume::validation.field') . ' ' . __('Resume::fields.jobs_position') . ' ' . __('Resume::validation.custom_required'),
                   'country_id' => __('Resume::validation.field') . ' ' . __('Resume::fields.jobs_country_id') . ' ' . __('Resume::validation.custom_required'),
                   'city_id' => __('Resume::validation.field') . ' ' . __('Resume::fields.jobs_city_id') . ' ' . __('Resume::validation.custom_required'),
                   'start_date' => __('Resume::validation.field') . ' ' . __('Resume::fields.jobs_start_date') . ' ' . __('Resume::validation.custom_required')
            ],
            'languages' => [
                'lang_id' => __('Resume::validation.field') . ' ' . __('Resume::fields.languages_lang_id') . ' ' . __('Resume::validation.custom_required'),
                'know_lang_id' => __('Resume::validation.field') . ' ' . __('Resume::fields.languages_know_lang_id') . ' ' . __('Resume::validation.custom_required'),
            ]
        ];
    }
}
