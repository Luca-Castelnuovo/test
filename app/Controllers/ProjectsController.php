<?php

namespace App\Controllers;

use DB;
use Exception;
use App\Helpers\SessionHelper;
use App\Validators\ProjectValidator;
use Zend\Diactoros\ServerRequest;
use Ramsey\Uuid\Uuid;

class ProjectsController extends Controller
{
    /**
     * Create project
     * 
     * @param ServerRequest $request
     * 
     * @return JsonResponse
     */
    public function create(ServerRequest $request)
    {
        try {
            ProjectValidator::create($request->data);
        } catch (Exception $e) {
            return $this->respondJson(
                'Provided data was malformed',
                json_decode($e->getMessage()),
                422
            );
        }

        if (DB::has('projects', [
            'name' => $request->data->name,
            'owner_id' => SessionHelper::get('id')
        ])) {
            return $this->respondJson(
                'Project with this name already exists',
                [],
                400
            );
        }

        // TODO: check tier

        $id = Uuid::uuid4()->toString();
        DB::create('projects', [
            'id' => $id,
            'name' => $request->data->name,
            'owner_id' => SessionHelper::get('id')
        ]);

        // create folder for user

        // prefill project - https://github.com/Luca-Castelnuovo/TestingPlatform/issues/33

        return $this->respondJson(
            'Project Created',
            ['redirect' => "/project/{$id}"]
        );
    }

    /**
     * Show project
     * 
     * @return RedirectResponse|HtmlResponse
     */
    public function view($id)
    {
        $project = DB::get('projects', ['name'], [
            'id' => $id,
            'owner_id' => SessionHelper::get('id')
        ]);

        if (!$project) {
            return $this->redirect('/dashboard');
        }

        $files = DB::select('files', ['name'], [
            'project_id' => $id,
            'owner_id' => SessionHelper::get('id')
        ]);

        return $this->respond('project.twig', [
            'project_id' => $id,
            'project_name' => $project['name'],
            'files' => $files
        ]);
    }

    /**
     * Delete project
     * 
     * @return JsonResponse
     */
    public function delete($id)
    {
        if (!DB::has('projects', [
            'id' => $id,
            'owner_id' => SessionHelper::get('id')
        ])) {
            return $this->respondJson(
                'Project not found',
                [],
                404
            );
        }

        DB::delete('projects', [
            'id' => $id,
            'owner_id' => SessionHelper::get('id')
        ]);

        return $this->respondJson(
            'Project Deleted',
            ['reload' => true]
        );
    }
}
