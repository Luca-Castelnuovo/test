<?php

namespace App\Controllers;

use DB;
use lucacastelnuovo\Helpers\Session;

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
            'owner_id' => Session::get('id'),
            'ORDER' => ['name' => 'ASC']
        ]);

        return $this->respond('dashboard.twig', ['projects' => $projects]);
    }
}
