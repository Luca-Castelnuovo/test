<?php

namespace App\Controllers;

use Exception;
use CQ\DB\DB;
use CQ\Helpers\UUID;
use CQ\Helpers\Session;
use CQ\Helpers\Variant;
use CQ\Controllers\Controller;
use App\Validators\FileValidator;

class FilesController extends Controller
{
    /**
     * Create file
     * 
     * @param object $request
     * @param string $project_id
     * 
     * @return Json
     */
    public function create($request, $project_id)
    {
        try {
            FileValidator::create($request->data);
        } catch (Exception $e) {
            return $this->respondJson(
                'Provided data was malformed',
                json_decode($e->getMessage()),
                422
            );
        }

        $owner_id = Session::get('id');
        $file_name = strtolower($request->data->name);
        $file_name = str_replace(' ', '_', $file_name);
        $file_type = substr(strrchr($file_name, '.'), 1);

        if (!DB::has('projects', [
            'id' => $project_id,
            'owner_id' => $owner_id
        ])) {
            return $this->respondJson(
                'Project not found',
                ['redirect' => '/dashboard'],
                404
            );
        }

        if (DB::has('files', [
            'name' => $file_name,
            'project_id' => $project_id,
            'owner_id' => $owner_id
        ])) {
            return $this->respondJson(
                'File with this name already exists',
                [],
                400
            );
        }

        if (!in_array($file_type, Variant::check(Session::get('variant'), 'allowed_extensions'))) {
            return $this->respondJson(
                'Incorrect file type',
                [],
                400
            );
        }

        $n_files = DB::count('files', [
            'owner_id' => $owner_id,
            'project_id' => $project_id
        ]);
        if (!Variant::check(Session::get('variant'), 'files_per_project', $n_files)) {
            $n_files_licensed = Variant::variantValue(Session::get('variant'), 'files_per_project');

            return $this->respondJson(
                "Files quota reached, max {$n_files_licensed}",
                [],
                400
            );
        }

        $id = UUID::v6();
        DB::create('files', [
            'id' => $id,
            'name' => $file_name,
            'project_id' => $project_id,
            'owner_id' => $owner_id
        ]);

        $file_path = "users/{$owner_id}/{$project_id}/{$file_name}";
        $file = fopen($file_path, "w");
        fclose($file);

        return $this->respondJson(
            'File Created',
            ['reload' => true]
        );
    }

    /**
     * Show file
     * 
     * @param string $id
     * 
     * @return RedirectResponse|HtmlResponse
     */
    public function view($id)
    {
        $owner_id = Session::get('id');

        $file = DB::get('files', ['id', 'name', 'project_id', 'updated_at'], [
            'id' => $id,
            'owner_id' => $owner_id
        ]);

        if (!$file) {
            return $this->redirect('/dashboard');
        }

        $file_path = "users/{$owner_id}/{$file['project_id']}/{$file['name']}";
        $file_open = fopen($file_path, "r");
        $file['content'] = fread($file_open, filesize($file_path));
        fclose($file_open);

        $file['ext'] = pathinfo($file_path, PATHINFO_EXTENSION);
        if ($file['ext'] === 'js') {
            $file['ext'] = 'javascript';
        }

        $file['updated_at'] = strtotime($file['updated_at']);

        return $this->respond('file.twig', [
            'file' => $file
        ]);
    }

    /**
     * Update file
     *
     * @param object $request
     * 
     * @return Json
     */
    public function update($request, $id)
    {
        try {
            FileValidator::update($request->data);
        } catch (Exception $e) {
            return $this->respondJson(
                'Provided data was malformed',
                json_decode($e->getMessage()),
                422
            );
        }

        $owner_id = Session::get('id');

        $file = DB::get('files', ['name', 'project_id'], [
            'id' => $id,
            'owner_id' => $owner_id
        ]);

        if (!$file) {
            return $this->respondJson(
                'File not found',
                ['reload' => true],
                400
            );
        }

        $file_path = "users/{$owner_id}/{$file['project_id']}/{$file['name']}";
        $file_open = fopen($file_path, "w");
        $file_content = htmlspecialchars_decode($request->data->content);
        fwrite($file_open, $file_content);
        fclose($file_open);

        if ($request->data->quit) {
            return $this->respondJson(
                'File Updated',
                ['redirect' => "/project/{$file['project_id']}"]
            );
        }

        return $this->respondJson('File Updated');
    }

    /**
     * Delete File
     * 
     * @param string $id
     * 
     * @return Json
     */
    public function delete($id)
    {
        $owner_id = Session::get('id');

        $file = DB::get('files', ['name', 'project_id'], [
            'id' => $id,
            'owner_id' => $owner_id
        ]);

        if (!$file) {
            return $this->respondJson(
                'File not found',
                ['reload' => true],
                400
            );
        }

        DB::delete('files', [
            'id' => $id,
            'owner_id' => Session::get('id')
        ]);

        $file_path = "users/{$owner_id}/{$file['project_id']}/{$file['name']}";
        unlink($file_path);

        return $this->respondJson(
            'File Deleted',
            ['reload' => true]
        );
    }
}
