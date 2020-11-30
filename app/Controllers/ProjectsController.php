<?php

namespace App\Controllers;

use App\Validators\ProjectValidator;
use CQ\Config\Config;
use CQ\Controllers\Controller;
use CQ\DB\DB;
use CQ\Helpers\User;
use CQ\Helpers\UUID;
use CQ\Helpers\Folder;
use CQ\Helpers\File;
use Exception;

class ProjectsController extends Controller
{
    /**
     * Create project.
     *
     * @param object $request
     *
     * @return Json
     */
    public function create($request)
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
            'owner_id' => User::getId(),
        ])) {
            return $this->respondJson(
                'Project with this name already exists',
                [],
                400
            );
        }

        $id = UUID::v6();
        $owner_id = User::getId();

        $user_n_projects = DB::count('projects', ['owner_id' => $owner_id]);
        $max_n_projects = User::valueRole('max_projects');
        if ($user_n_projects >= $max_n_projects) {
            return $this->respondJson(
                "Projects quota reached, max {$max_n_projects}",
                [],
                400
            );
        }

        $project_path = "users/{$owner_id}/{$id}";
        $template_path = '../views/templates';

        try {
            Folder::create($project_path);
            File::copy("{$template_path}/index.html", "{$project_path}/index.html");
            File::copy("{$template_path}/style.css", "{$project_path}/style.css");
            File::copy("{$template_path}/index.js", "{$project_path}/index.js");
        } catch (Exception $e) {
            return $this->respondJson(
                "Project couldn't be created",
                json_decode($e->getMessage()),
                500
            );
        }

        DB::create('projects', [
            'id' => $id,
            'name' => $request->data->name,
            'owner_id' => $owner_id,
        ]);

        DB::create('files', [
            'id' => UUID::v4(),
            'project_id' => $id,
            'owner_id' => $owner_id,
            'name' => 'index.html',
        ]);

        DB::create('files', [
            'id' => UUID::v4(),
            'project_id' => $id,
            'owner_id' => $owner_id,
            'name' => 'style.css',
        ]);

        DB::create('files', [
            'id' => UUID::v4(),
            'project_id' => $id,
            'owner_id' => $owner_id,
            'name' => 'index.js',
        ]);

        return $this->respondJson(
            'Project Created',
            ['redirect' => "/project/{$id}"]
        );
    }

    /**
     * Show project.
     *
     * @param mixed $id
     *
     * @return RedirectResponse|HtmlResponse
     */
    public function view($id)
    {
        $project = DB::get('projects', ['name'], [
            'id' => $id,
            'owner_id' => User::getId(),
        ]);

        if (!$project) {
            return $this->redirect('/dashboard');
        }

        $files = DB::select('files', ['id', 'name'], [
            'project_id' => $id,
            'owner_id' => User::getId(),
            'ORDER' => ['name' => 'ASC'],
        ]);

        return $this->respond('project.twig', [
            'app_url' => Config::get('app.url'),
            'user_id' => User::getId(),
            'project_id' => $id,
            'project_name' => $project['name'],
            'files' => $files,
        ]);
    }

    /**
     * Delete project.
     *
     * @param mixed $id
     *
     * @return JsonResponse
     */
    public function delete($id)
    {
        if (!DB::has('projects', [
            'id' => $id,
            'owner_id' => User::getId(),
        ])) {
            return $this->respondJson(
                'Project not found',
                [],
                404
            );
        }

        $owner_id = User::getId();

        try {
            Folder::delete("users/{$owner_id}/{$id}", true);
        } catch (Exception $e) {
            return $this->respondJson(
                "Project couldn't be deleted",
                json_decode($e->getMessage()),
                500
            );
        }

        DB::delete('projects', [
            'id' => $id,
            'owner_id' => User::getId(),
        ]);

        DB::delete('files', [
            'project_id' => $id,
            'owner_id' => User::getId(),
        ]);

        return $this->respondJson(
            'Project Deleted',
            ['reload' => true]
        );
    }
}
