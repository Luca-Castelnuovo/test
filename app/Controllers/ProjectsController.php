<?php

namespace App\Controllers;

use Exception;
use CQ\DB\DB;
use CQ\Config\Config;
use CQ\Helpers\UUID;
use CQ\Helpers\Session;
use CQ\Helpers\Variant;
use CQ\Controllers\Controller;
use App\Validators\ProjectValidator;


class ProjectsController extends Controller
{
    /**
     * Create project
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
            'owner_id' => Session::get('id')
        ])) {
            return $this->respondJson(
                'Project with this name already exists',
                [],
                400
            );
        }

        $id = UUID::v6();
        $owner_id = Session::get('id');

        $n_projects = DB::count('projects', ['owner_id' => $owner_id]);
        if (Variant::check(Session::get('variant'), 'max_projects', $n_projects)) {
            $n_projects_licensed = Variant::variantValue(Session::get('variant'), 'max_projects');

            return $this->respondJson(
                "Projects quota reached, max {$n_projects_licensed}",
                [],
                400
            );
        }

        DB::create('projects', [
            'id' => $id,
            'name' => $request->data->name,
            'owner_id' => $owner_id
        ]);

        $project_path = "users/{$owner_id}/{$id}";
        $template_path = "../views/templates";
        mkdir($project_path, 0770);

        copy($template_path . '/index.html', $project_path . '/index.html');
        $indexhtml = file_get_contents($project_path . '/index.html');
        $base_href = Config::get('app.url') . '/' . $project_path . '/';
        $indexhtml = str_replace('BASE_HREF', $base_href, $indexhtml);
        file_put_contents($project_path . '/index.html', $indexhtml);
        DB::create('files', [
            'id' => UUID::v4(),
            'project_id' => $id,
            'owner_id' => $owner_id,
            'name' => 'index.html'
        ]);

        copy($template_path . '/style.css', $project_path . '/style.css');
        DB::create('files', [
            'id' => UUID::v4(),
            'project_id' => $id,
            'owner_id' => $owner_id,
            'name' => 'style.css'
        ]);

        copy($template_path . '/index.js', $project_path . '/index.js');
        DB::create('files', [
            'id' => UUID::v4(),
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
            'owner_id' => Session::get('id')
        ]);

        if (!$project) {
            return $this->redirect('/dashboard');
        }

        $files = DB::select('files', ['id', 'name'], [
            'project_id' => $id,
            'owner_id' => Session::get('id'),
            'ORDER' => ['name' => 'ASC']
        ]);

        return $this->respond('project.twig', [
            'app_url' => Config::get('app.url'),
            'user_id' => Session::get('id'),
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
            'owner_id' => Session::get('id')
        ])) {
            return $this->respondJson(
                'Project not found',
                [],
                404
            );
        }

        DB::delete('projects', [
            'id' => $id,
            'owner_id' => Session::get('id')
        ]);

        DB::delete('files', [
            'project_id' => $id,
            'owner_id' => Session::get('id')
        ]);

        $owner_id = Session::get('id');
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
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
}
