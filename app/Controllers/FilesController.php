<?php

namespace App\Controllers;

use App\Validators\FileValidator;
use CQ\Controllers\Controller;
use CQ\DB\DB;
use CQ\Helpers\User;
use CQ\Helpers\UUID;
use CQ\Helpers\File;
use Exception;

class FilesController extends Controller
{
    /**
     * Create file.
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

        $owner_id = User::getId();
        $file_name = strtolower($request->data->name);
        $file_name = str_replace(' ', '_', $file_name);
        $file_type = substr(strrchr($file_name, '.'), 1);

        if (!DB::has('projects', [
            'id' => $project_id,
            'owner_id' => $owner_id,
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
            'owner_id' => $owner_id,
        ])) {
            return $this->respondJson(
                'File with this name already exists',
                [],
                400
            );
        }

        $allowed_extensions = User::valueRole('allowed_extensions');
        if (!in_array($file_type, $allowed_extensions)) {
            return $this->respondJson(
                'Incorrect file type',
                [],
                400
            );
        }

        $user_n_files = DB::count('files', ['owner_id' => $owner_id, 'project_id' => $project_id]);
        $max_n_files = User::valueRole('files_per_project');
        if ($user_n_files >= $max_n_files) {
            return $this->respondJson(
                "Files quota reached, max {$max_n_files}",
                [],
                400
            );
        }

        try {
            $file = new File("users/{$owner_id}/{$project_id}/{$file_name}");
            $file->create();
        } catch (Exception $e) {
            return $this->respondJson(
                "File couldn't be created",
                [],
                400
            );
        }

        $id = UUID::v6();
        DB::create('files', [
            'id' => $id,
            'name' => $file_name,
            'project_id' => $project_id,
            'owner_id' => $owner_id,
        ]);

        return $this->respondJson(
            'File Created',
            ['reload' => true]
        );
    }

    /**
     * Show file.
     *
     * @param string $id
     *
     * @return RedirectResponse|HtmlResponse
     */
    public function view($id)
    {
        $owner_id = User::getId();

        $file = DB::get('files', ['id', 'name', 'project_id', 'updated_at'], [
            'id' => $id,
            'owner_id' => $owner_id,
        ]);

        if (!$file) {
            return $this->redirect('/dashboard');
        }

        $fileHelper = new File("users/{$owner_id}/{$file['project_id']}/{$file['name']}");

        $file['content'] = $fileHelper->read();
        $file['updated_at'] = strtotime($file['updated_at']);
        $file['ext'] = $fileHelper->getPathInfo()['extension'];
        if ('js' === $file['ext']) {
            $file['ext'] = 'javascript';
        }

        return $this->respond('file.twig', [
            'file' => $file,
        ]);
    }

    /**
     * Update file.
     *
     * @param object $request
     * @param mixed  $id
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

        $owner_id = User::getId();

        $file = DB::get('files', ['name', 'project_id'], [
            'id' => $id,
            'owner_id' => $owner_id,
        ]);

        if (!$file) {
            return $this->respondJson(
                'File not found',
                ['reload' => true],
                400
            );
        }

        $fileHelper = new File("users/{$owner_id}/{$file['project_id']}/{$file['name']}");
        $fileHelper->write(htmlspecialchars_decode($request->data->content));

        if ($request->data->quit) {
            return $this->respondJson(
                'File Updated',
                ['redirect' => "/project/{$file['project_id']}"]
            );
        }

        return $this->respondJson('File Updated');
    }

    /**
     * Delete File.
     *
     * @param string $id
     *
     * @return Json
     */
    public function delete($id)
    {
        $owner_id = User::getId();

        $file = DB::get('files', ['name', 'project_id'], [
            'id' => $id,
            'owner_id' => $owner_id,
        ]);

        if (!$file) {
            return $this->respondJson(
                'File not found',
                ['reload' => true],
                400
            );
        }

        try {
            $fileHelper = new File("users/{$owner_id}/{$file['project_id']}/{$file['name']}");
            $fileHelper->delete();
        } catch (Exception $e) {
            return $this->respondJson(
                'File not deleted',
                ['reload' => true],
                400
            );
        }

        DB::delete('files', [
            'id' => $id,
            'owner_id' => User::getId(),
        ]);

        return $this->respondJson(
            'File Deleted',
            ['reload' => true]
        );
    }
}
