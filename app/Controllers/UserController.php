<?php

namespace App\Controllers;

use CQ\Controllers\Controller;
use CQ\DB\DB;
use CQ\Helpers\Session;

class UserController extends Controller
{
    /**
     * Dashboard screen.
     *
     * @return Html
     */
    public function dashboard()
    {
        $projects = DB::select('projects', ['id', 'name'], [
            'owner_id' => Session::get('id'),
            'ORDER' => ['name' => 'ASC'],
        ]);

        return $this->respond('dashboard.twig', ['projects' => $projects]);
    }
}
