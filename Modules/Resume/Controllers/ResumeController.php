<?php

namespace App\Modules\Resume\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Resume\Requests\ResumeFormRequest;
use App\Modules\Resume\Service\InterfaceResumeService;
use App\Modules\Resume\Resources\ResumeCollection;
use App\Modules\Resume\Resources\ResumeResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ResumeController extends Controller
{
     private InterfaceResumeService $resumeService;

     public function __construct(InterfaceResumeService $resumeService)
     {
        $this->resumeService = $resumeService;
     }

    /**
     * @param Request $request
     * @return ResumeCollection
     * @OA\Get(
     *   path="/api/v1/resumes",
     *   tags={"Resumes"},
     *   summary="collection paginate of resumes",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="success"
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="bad request"
     *   )
     * )
     */
     public function items(Request $request): ResumeCollection
     {
        $resumes = $this->resumeService->collection($request->all(),'id','desc');
        return new ResumeCollection($resumes);
     }

    /**
     * @param int $id
     * @return ResumeResource|null
     * @OA\Get (
     *   path="/api/v1/resumes/{id}",
     *   tags={"Resumes"},
     *   summary="get resume item",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="success"
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="bad request"
     *   )
     * )
     */
     public function findItem(int $id): ?ResumeResource
     {
         $resume = $this->resumeService->getItem($id);
         return new ResumeResource($resume);
     }

    /**
     * @param int $id
     * @return ResumeResource|null
     * @OA\Get (
     *   path="/api/v1/resumes/user/{id}",
     *   tags={"Resumes"},
     *   summary="get resume item of user",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="success"
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="bad request"
     *   )
     * )
     */
    public function findItemOfUser(int $id): ?ResumeResource
     {
         $resume = $this->resumeService->getItemUserResume($id);
         return new ResumeResource($resume);
     }

    /**
     * @param ResumeFormRequest $resume
     * @return ResumeResource
     * @OA\Post(
     *   path="/api/v1/resumes/create",
     *   tags={"Resumes"},
     *   summary="resume create",
     *   security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               type="object",
     *               required={"user_id",
     *                         "iin",
     *                         "birthday",
     *                         "gender_id",
     *                         "nationality_id",
     *                         "citizenship_id",
     *                         "mobile",
     *                         "family_status_id",
     *                         "country_id",
     *                         "country_area_id",
     *                         "city_id",
     *                         "is_work_experience",
     *                         "is_driver_license",
     *                         "is_criminal_info",
     *                         "is_military_service",
     *                         "is_nak_job",
     *                         "position",
     *                         "start_date",
     *                         "edu_title",
     *                         "speciality",
     *                         "edu_type_id"},
     *               @OA\Property(property="user_id", type="integer"),
     *               @OA\Property(property="iin", type="integer", minLength=12, maxLength=12),
     *               @OA\Property(property="birthday", type="string", format="date"),
     *               @OA\Property(property="gender_id", type="integer"),
     *               @OA\Property(property="nationality_id", type="integer"),
     *               @OA\Property(property="citizenship_id", type="integer", default="1"),
     *               @OA\Property(property="mobile", type="integer", minLength=10, maxLength=10),
     *               @OA\Property(property="family_status_id", type="integer"),
     *               @OA\Property(property="country_id", type="integer"),
     *               @OA\Property(property="country_area_id", type="integer"),
     *               @OA\Property(property="city_id", type="integer"),
     *               @OA\Property(property="is_work_experience", type="integer", minLength=1, default="0"),
     *               @OA\Property(property="is_driver_license", type="integer", minLength=1, default="0"),
     *               @OA\Property(property="is_criminal_info", type="integer", minLength=1, default="0"),
     *               @OA\Property(property="is_military_service", type="integer", minLength=1, default="0"),
     *               @OA\Property(property="salary_level", type="string"),
     *               @OA\Property(property="know_softs", type="string"),
     *               @OA\Property(property="bussines_trip_id", type="integer"),
     *               @OA\Property(property="about_me", type="string"),
     *               @OA\Property(property="hobby", type="string"),
     *               @OA\Property(property="hh_id", type="string"),
     *               @OA\Property(property="jobs",
     *                            type="array",
     *                            @OA\Items(
     *                              required={"user_id",
     *                                        "direction_id",
     *                                        "position",
     *                                        "country_id",
     *                                        "start_date"},
     *                              @OA\Property(property="user_id", type="integer",default="0"),
     *                              @OA\Property(property="company_id", type="integer"),
     *                              @OA\Property(property="company_title", type="string"),
     *                              @OA\Property(property="is_nak_job", type="integer", default="0"),
     *                              @OA\Property(property="direction_id", type="integer"),
     *                              @OA\Property(property="position", type="string"),
     *                              @OA\Property(property="country_id", type="integer"),
     *                              @OA\Property(property="city_id", type="string"),
     *                              @OA\Property(property="city_title", type="string"),
     *                              @OA\Property(property="start_date", type="string", format="date"),
     *                              @OA\Property(property="end_date", type="string", format="date"),
     *                              @OA\Property(property="is_current_time", type="integer", default="0"),
     *                              @OA\Property(property="publications", type="string"),
     *                              @OA\Property(property="official_duties", type="string"),
     *                            )
     *               ),
     *              @OA\Property(property="educations",
     *                            type="array",
     *                            @OA\Items(
     *                              required={
     *                                 "edu_title",
     *                                 "speciality",
     *                                 "edu_type_id",
     *                                 "country_id",
     *                                 "start_date",
     *                                 "end_date"
     *                              },
     *                              @OA\Property(property="edu_title", type="string"),
     *                              @OA\Property(property="speciality", type="string"),
     *                              @OA\Property(property="edu_type_id", type="integer"),
     *                              @OA\Property(property="country_id", type="integer"),
     *                              @OA\Property(property="city_id", type="integer"),
     *                              @OA\Property(property="city_title", type="string"),
     *                              @OA\Property(property="start_date", type="string", format="date"),
     *                              @OA\Property(property="end_date", type="string", format="date"),
     *                              @OA\Property(property="specialization", type="string"),
     *                              @OA\Property(property="academic_degree", type="string"),
     *                              @OA\Property(property="document_id", type="integer"),
     *                              @OA\Property(property="publications", type="string"),
     *                            )
     *               ),
     *               @OA\Property(property="languages",
     *                            type="array",
     *                            @OA\Items(
     *                              required={
     *                                 "lang_id",
     *                                 "know_lang_id"
     *                              },
     *                              @OA\Property(property="lang_id", type="integer"),
     *                              @OA\Property(property="know_lang_id", type="integer")
     *                           )
     *              ),
     *               @OA\Property(property="trainings",
     *                            type="array",
     *                            @OA\Items(
     *                              required={
     *                                 "course_title",
     *                                 "org_title",
     *                                 "country_id",
     *                                 "start_date",
     *                                 "end_date"
     *                              },
     *                              @OA\Property(property="course_title", type="string"),
     *                              @OA\Property(property="org_title", type="string"),
     *                              @OA\Property(property="country_id", type="integer"),
     *                              @OA\Property(property="city_id", type="integer"),
     *                              @OA\Property(property="city", type="integer"),
     *                              @OA\Property(property="start_date", type="string",format="date"),
     *                              @OA\Property(property="end_date", type="string",format="date")
     *                           )
     *              )
     *            ),
     *          ),
     *        ),
     *   @OA\Response(
     *     response=200,
     *     description="success"
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="bad request"
     *   )
     * )
     */
     public function createItem(ResumeFormRequest $resume): ResumeResource
     {
         $data = $resume->all();
         $data['user_id'] = request()->input('user_id');
         $resume = $this->resumeService->setItem($data);
         return new ResumeResource($resume);
     }

    /**
     * @param ResumeFormRequest $resume
     * @param int $id
     * @return ResumeResource
     * @OA\Patch(
     *   path="/api/v1/resumes/{id}",
     *   tags={"Resumes"},
     *   summary="resume update",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *    @OA\RequestBody(
     *          @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               type="object",
     *               required={"id",
     *                         "user_id",
     *                         "iin",
     *                         "birthday",
     *                         "gender_id",
     *                         "nationality_id",
     *                         "citizenship_id",
     *                         "mobile",
     *                         "family_status_id",
     *                         "country_id",
     *                         "country_area_id",
     *                         "city_id",
     *                         "is_work_experience",
     *                         "is_driver_license",
     *                         "is_criminal_info",
     *                         "is_military_service",
     *                         "is_nak_job",
     *                         "position",
     *                         "start_date",
     *                         "edu_title",
     *                         "speciality",
     *                         "edu_type_id"},
     *               @OA\Property(property="id", type="integer"),
     *               @OA\Property(property="user_id", type="integer"),
     *               @OA\Property(property="iin", type="integer", minLength=12, maxLength=12),
     *               @OA\Property(property="birthday", type="string", format="date"),
     *               @OA\Property(property="gender_id", type="integer"),
     *               @OA\Property(property="nationality_id", type="integer"),
     *               @OA\Property(property="citizenship_id", type="integer", default="1"),
     *               @OA\Property(property="mobile", type="integer", minLength=10, maxLength=10),
     *               @OA\Property(property="family_status_id", type="integer"),
     *               @OA\Property(property="country_id", type="integer"),
     *               @OA\Property(property="country_area_id", type="integer"),
     *               @OA\Property(property="city_id", type="integer"),
     *               @OA\Property(property="is_work_experience", type="integer", minLength=1, default="0"),
     *               @OA\Property(property="is_driver_license", type="integer", minLength=1, default="0"),
     *               @OA\Property(property="is_criminal_info", type="integer", minLength=1, default="0"),
     *               @OA\Property(property="is_military_service", type="integer", minLength=1, default="0"),
     *               @OA\Property(property="salary_level", type="string"),
     *               @OA\Property(property="know_softs", type="string"),
     *               @OA\Property(property="bussines_trip_id", type="integer"),
     *               @OA\Property(property="about_me", type="string"),
     *               @OA\Property(property="hobby", type="string"),
     *               @OA\Property(property="hh_id", type="string"),
     *               @OA\Property(property="jobs",
     *                            type="array",
     *                            @OA\Items(
     *                              required={"id",
     *                                        "user_id",
     *                                        "direction_id",
     *                                        "position",
     *                                        "country_id",
     *                                        "start_date"},
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="user_id", type="integer",default="0"),
     *                              @OA\Property(property="company_id", type="integer"),
     *                              @OA\Property(property="company_title", type="string"),
     *                              @OA\Property(property="is_nak_job", type="integer", default="0"),
     *                              @OA\Property(property="direction_id", type="integer"),
     *                              @OA\Property(property="position", type="string"),
     *                              @OA\Property(property="country_id", type="integer"),
     *                              @OA\Property(property="city_id", type="string"),
     *                              @OA\Property(property="city_title", type="string"),
     *                              @OA\Property(property="start_date", type="string", format="date"),
     *                              @OA\Property(property="end_date", type="string", format="date"),
     *                              @OA\Property(property="is_current_time", type="integer", default="0"),
     *                              @OA\Property(property="publications", type="string"),
     *                              @OA\Property(property="official_duties", type="string"),
     *                            )
     *               ),
     *              @OA\Property(property="educations",
     *                            type="array",
     *                            @OA\Items(
     *                              required={
     *                                 "id",
     *                                 "edu_title",
     *                                 "speciality",
     *                                 "edu_type_id",
     *                                 "country_id",
     *                                 "start_date",
     *                                 "end_date"
     *                              },
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="edu_title", type="string"),
     *                              @OA\Property(property="speciality", type="string"),
     *                              @OA\Property(property="edu_type_id", type="integer"),
     *                              @OA\Property(property="country_id", type="integer"),
     *                              @OA\Property(property="city_id", type="integer"),
     *                              @OA\Property(property="city_title", type="string"),
     *                              @OA\Property(property="start_date", type="string", format="date"),
     *                              @OA\Property(property="end_date", type="string", format="date"),
     *                              @OA\Property(property="specialization", type="string"),
     *                              @OA\Property(property="academic_degree", type="string"),
     *                              @OA\Property(property="document_id", type="integer"),
     *                              @OA\Property(property="publications", type="string"),
     *                            )
     *               ),
     *               @OA\Property(property="languages",
     *                            type="array",
     *                            @OA\Items(
     *                              required={
     *                                 "id",
     *                                 "lang_id",
     *                                 "know_lang_id"
     *                              },
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="lang_id", type="integer"),
     *                              @OA\Property(property="know_lang_id", type="integer")
     *                           )
     *              ),
     *              @OA\Property(property="trainings",
     *                            type="array",
     *                            @OA\Items(
     *                              required={
     *                                 "id",
     *                                 "course_title",
     *                                 "org_title",
     *                                 "country_id",
     *                                 "start_date",
     *                                 "end_date"
     *                              },
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="course_title", type="string"),
     *                              @OA\Property(property="org_title", type="string"),
     *                              @OA\Property(property="country_id", type="integer"),
     *                              @OA\Property(property="city_id", type="integer"),
     *                              @OA\Property(property="city", type="integer"),
     *                              @OA\Property(property="start_date", type="string",format="date"),
     *                              @OA\Property(property="end_date", type="string",format="date")
     *                           )
     *                       )
     *            ),
     *          ),
     *        ),
     *   @OA\Response(
     *     response=200,
     *     description="success"
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="bad request"
     *   )
     * )
     */
     public function updateItem(ResumeFormRequest $resume, int $id): ResumeResource
     {
         $data = $resume->all();
         $data['user_id'] = request()->input('user_id');
         $resume = $this->resumeService->setItem($data, $id);
         return new ResumeResource($resume);
     }

    /**
     * @param int $id
     * @OA\Delete(
     *   path="/api/v1/resumes/{id}",
     *   tags={"Resumes"},
     *   summary="remove resume item",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="success"
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="bad request"
     *   )
     * )
     */
     public function removeItem(int $id): void
     {
         $this->resumeService->remove($id);
     }

    /**
     * @param int $id
     * @OA\Delete(
     *   path="/api/v1/resumes/relation/{id}",
     *   tags={"Resumes"},
     *   summary="remove resume relation in item",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               type="object",
     *               required={"relation_name"},
     *               @OA\Property(property="relation_name", type="string")
     *           )
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="success"
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="bad request"
     *   )
     * )
     */
     public function removeRelation(int $id): void
     {
         $relation_name = \request()->input('relation_name');
         $this->resumeService->removeRelation($id, $relation_name);
     }

    /**
     * @param int $id
     * @OA\Post(
     *   path="/api/v1/resumes/download/{id}",
     *   tags={"Resumes"},
     *   summary="download resume",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="success"
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="bad request"
     *   )
     * )
     */
     public function downloadResume(int $id)
     {
        return $this->resumeService->generateResume($id);
     }
}
