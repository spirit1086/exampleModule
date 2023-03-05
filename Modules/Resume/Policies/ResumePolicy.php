<?php

namespace App\Modules\Resume\Policies;

use App\Modules\Resume\Service\InterfaceResumeService;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResumePolicy
{
    use HandlesAuthorization;

    private array $rules;
    private const MODULE = 'resumes';
    private const ACTIONS = [
        "read" => false,
        "create" => false,
        "edit" => false,
        "editOwn" => false,
        "update" => false,
        "delete" => false,
        "download_resume" => false
    ];
    private $resumeService;
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(InterfaceResumeService $resumeService)
    {
        $this->rules = request()->input('rbac')['rules'][self::MODULE] ?? self::ACTIONS;
        $this->resumeService = $resumeService;
    }

    public function read()
    {
        return $this->rules['read'];
    }

    public function create()
    {
        return $this->rules['create'];
    }

    public function edit()
    {
        return $this->rules['edit'];
    }

    public function editOwn()
    {
       $permission = false;
       $user_id = request()->input('auth_user_id');
       $editOwn = $this->rules['editOwn'];
       $resume = $this->resumeService->getItemUserResume(request()->resume_user_id);
       if ($this->edit() || ($editOwn && $resume->user_id == $user_id)) {
           $permission = true;
       }
       return $permission;
    }

    public function update()
    {
        return $this->rules['update'];
    }

    public function delete()
    {
        return $this->rules['update'];
    }

    public function download_resume()
    {
        return $this->rules['download_resume'];
    }
}
