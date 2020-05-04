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
        $owner_id = SessionHelper::get('id');

        DB::create('projects', [
            'id' => $id,
            'name' => $request->data->name,
            'owner_id' => $owner_id
        ]);

        $project_path = "users/{$owner_id}/{$id}";
        $template_path = "../views/templates";
        mkdir($project_path, 0770);

        copy($template_path . '/index.html', $project_path . '/index.html');
        DB::create('files', [
            'id' => Uuid::uuid4()->toString(),
            'project_id' => $id,
            'owner_id' => $owner_id,
            'name' => 'index.html'
        ]);

        copy($template_path . '/style.css', $project_path . '/style.css');
        DB::create('files', [
            'id' => Uuid::uuid4()->toString(),
            'project_id' => $id,
            'owner_id' => $owner_id,
            'name' => 'style.css'
        ]);

        copy($template_path . '/index.js', $project_path . '/index.js');
        DB::create('files', [
            'id' => Uuid::uuid4()->toString(),
            'project_id' => $id,
            'owner_id' => $owner_id,
            'name' => 'index.js'
        ]);

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

        DB::delete('files', [
            'project_id' => $id,
            'owner_id' => SessionHelper::get('id')
        ]);

        $owner_id = SessionHelper::get('id');
        $this->rrmdir("users/{$owner_id}/{$id}");

        return $this->respondJson(
            'Project Deleted',
            ['reload' => true]
        );
    }

    /**
     * Recursively delete dierctory
     *
     * @param string $dir
     * 
     * @return void
     */
    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
}
