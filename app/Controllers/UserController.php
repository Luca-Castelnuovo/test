<?php

namespace App\Controllers;

use CQ\DB\DB;
use CQ\Helpers\Session;
use CQ\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Dashboard screen
     * 
     * @return Html
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
