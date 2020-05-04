<?php

namespace App\Controllers;

use DB;
use App\Helpers\SessionHelper;

class UserController extends Controller
{
    /**
     * Dashboard screen
     * 
     * @return HtmlResponse
     */
    public function dashboard()
    {
        $projects = DB::select('projects', ['id', 'name'], [
            'owner_id' => SessionHelper::get('id'),
            'ORDER' => ['name' => 'ASC']
        ]);

        return $this->respond('dashboard.twig', ['projects' => $projects]);
    }
}
