<?php

namespace App\Controllers;

use DB;
use Exception;
use App\Helpers\SessionHelper;
use App\Validators\FileValidator;
use Zend\Diactoros\ServerRequest;
use Ramsey\Uuid\Uuid;

class FilesController extends Controller
{
    /**
     * Create file
     * 
     * @param ServerRequest $request
     * @param string $project_id
     * 
     * @return JsonResponse
     */
    public function create(ServerRequest $request, $project_id)
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

        $owner_id = SessionHelper::get('id');

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
            'name' => $request->data->name,
            'project_id' => $project_id,
            'owner_id' => $owner_id
        ])) {
            return $this->respondJson(
                'File with this name already exists',
                [],
                400
            );
        }

        // TODO: check it has valid extension

        // TODO: check tier

        $id = Uuid::uuid4()->toString();
        DB::create('files', [
            'id' => $id,
            'name' => $request->data->name,
            'project_id' => $project_id,
            'owner_id' => $owner_id
        ]);

        $file_path = "users/{$owner_id}/{$project_id}/{$request->data->name}";
        // TODO: create file

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
        $owner_id = SessionHelper::get('id');

        $file = DB::get('files', ['name', 'project_id'], [
            'id' => $id,
            'owner_id' => $owner_id
        ]);

        if (!$file) {
            return $this->redirect('/dashboard');
        }

        $file_path = "users/{$owner_id}/{$file['project_id']}/{$file['name']}";
        $file_content = ''; // TODO: get file content

        return $this->respond('file.twig', [
            'file_id' => $id,
            'file_content' => $file_content
        ]);
    }

    /**
     * Update file
     *
     * @param ServerRequest $request
     * 
     * @return JsonResponse
     */
    public function update(ServerRequest $request, $id)
    {
        $owner_id = SessionHelper::get('id');

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
        // TODO: write $request->data->content

        if ($request->data->quit) {
            return $this->respondJson(
                'File Updated',
                ['redirect' => "/project/{$file['project_id']}"]
            );
        }

        return $this->respondJson(
            'File Updated',
            ['reload' => true]
        );
    }

    /**
     * Delete File
     * 
     * @param string $id
     * 
     * @return JsonResponse
     */
    public function delete($id)
    {
        $owner_id = SessionHelper::get('id');

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
            'owner_id' => SessionHelper::get('id')
        ]);

        $file_path = "users/{$owner_id}/{$file['project_id']}/{$file['name']}";
        // TODO: delete actual file

        return $this->respondJson(
            'File Deleted',
            ['reload' => true]
        );
    }
}
