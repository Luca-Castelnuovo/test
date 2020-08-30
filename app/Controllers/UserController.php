<?php

namespace App\Controllers;

use CQ\Controllers\Controller;
use CQ\Helpers\User;
use CQ\DB\DB;

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
            'owner_id' => User::getId(),
            'ORDER' => ['name' => 'ASC'],
        ]);

        return $this->respond('dashboard.twig', ['projects' => $projects]);
    }
}
